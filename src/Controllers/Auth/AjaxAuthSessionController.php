<?php

namespace PavelVasilyev\AuthAjax\Controllers\Auth;

use App\Http\Controllers\Controller;
//use App\Http\Requests\Auth\LoginRequest;
//use App\Mail\Auth\AjaxVerifyEmail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Mail\Auth\PasswordResetMail;
use Illuminate\Validation\ValidationException;
use phpDocumentor\Reflection\Types\Boolean;
use Illuminate\Support\Facades\Password;

class AjaxAuthSessionController extends Controller
{
    // Импортируем трейт с методом respond (отправка ответа в браузер для вывода в модальном окне):
    use AjaxRespond;

    public object $request;
    protected string $loginType;
    protected string $userEmail;
    protected string $userLogin;
    protected string $userPassword;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->userLogin = trim($this->request->name);
        $this->userPassword = trim($this->request->password);
        if ($this->request->has('email')){
            $this->userEmail = trim($this->request->email);
        }
    }

    public function handle()
    {
        switch ($this->request->input('action')) {
            case 'log-form':
                return $this->formLogin();
                break;
            case 'login':
                return $this->login();
                break;
            case 'forgot-password':
                $resetPassword = new AjaxPasswordResetController();
                return $resetPassword->forgotPasswordForm();
                break;
            case 'forgot-password-data':
                $resetPassword = new AjaxPasswordResetController();
                return $resetPassword->forgotPasswordHandler($this->request, $this->userEmail);
                break;
            case 'reset-password':
                $resetPassword = new AjaxPasswordResetController();
                return $resetPassword->store($this->request);
                break;
            case 'logout-form':
                return $this->formLogout();
                break;
            case 'logout':
                return $this->destroy();
                break;
            default:
                //throw new \Exception('Не определён тип запроса (AjaxRegisterController)');
                return $this->respond( false, 'Ошибка приложения', 'Не определён тип запроса');
        }
    }

    /**
     * Confirm logout form:
     */
    public function formLogout()
    {
        $message = view('auth.logout-form')->render();
        return $this->respond( true, __('Logout name'), $message );
    }

    /**
     * Destroy an authenticated session (logout):
     */
    public function destroy()
    {
        Auth::guard('web')->logout();

        $this->request->session()->invalidate();

        $this->request->session()->regenerateToken();

        $this->request->session()->flash('logout', true); // индикатор для middleware 'auth.ajax'

        return response()->json([
            'ok' => true
        ]);
    }

    /**
     * Authenticate form view:
     */
    protected function formLogin(): \Illuminate\Http\JsonResponse
    {
        $message = view('auth.login-form', ['toPath' => $this->request->input('toPath')])->render();
        return $this->respond( true, __('Login title'), $message );
    }

    public function login()
    {
        // Empty inputs check:
        $errors = [];
        if(empty($this->userLogin)){
            $errors['name'] = ['message' => __('Your login or email')];
        }
        if(empty($this->userPassword)){
            $errors['password'] = ['message' => __('Your password')];
        }

        if( !empty($errors) ){
            $old = $this->request->flash();
            $message = view('auth.login-form')->withErrors($errors)->withInput($old)->render();
            return $this->respond( false, __('Login title'), $message );
        }

        // Login type definition (email or name):
        $this->loginType = $this->checkLoginInput();

        // Ограничение числа попыток входа:
        $throttleRes = $this->ensureIsNotRateLimited();
        if (! $throttleRes === false){
            return $this->respond( false, __('Login title'), $throttleRes );
        }
        RateLimiter::hit($this->throttleKey(), config('auth.rate_limiter.decay'));

        // User check:
        $user = User::where($this->loginType, $this->userLogin)->whereNotNull('email_verified_at')->first();
        if(!$user){
            $errors = ['warning' => ['message' => __('Incorrect credentials')]];
            $old = $this->request->flash();
            $message = view('auth.login-form')->withErrors($errors)->withInput($old)->render();
            return $this->respond( false, __('Login title'), $message );
        }

        // Authentication:
        $credentials = [
            $this->loginType => $this->userLogin,
            'password' => $this->userPassword
        ];

        // Получаем путь к целевой странице:
        if (!is_null($this->request->input('toPath')) && $this->request->input('toPath') !== '' && $this->request->input('toPath') !== false && $this->request->input('toPath') !== 'undefined'){
            $toPath = $this->request->input('toPath');
        } else {
            $toPath = substr(url()->previous(), strlen(env('APP_URL'))+1);
        }

        if (Auth::attempt($credentials, $this->request->boolean('remember'))) {
            $this->request->session()->regenerate();
            RateLimiter::clear($this->throttleKey());
            // Уведомление - в сессию. При редиректе оно попадёт в код modal в представлении, js его увидит и запустит modal сразу после загрузки страницы:
            $this->request->session()->put('onload-modal', [
                'title' => __('Login title'),
                'message' => '<div class="alert alert-success">'.__('successful authentication', ['name' => Auth::user()->name]).'</div>'
            ]);

            return $this->respond( true, $toPath );
        }

        // Authentication is false:
        $errors = ['warning' => ['message' => __('Incorrect credentials')]];
        $old = $this->request->flash();
        $message = view('auth.login-form')->withErrors($errors)->withInput($old)->render();
        return $this->respond( false, __('Login title'), $message );
    }

    /**
     * Login type definition:
     */
    protected function checkLoginInput()
    {
        //$regex = "/^[a-z0-9']+(-|_|'|\.|\+)?([a-z0-9]+)+@[a-zа-я0-9]+\.?([-a-z_а-я0-9]+)*\.{1}([a-zа-я]{2,10})$/isu"; // после @ разрешена кириллица
        //return = (!preg_match($regex,$this->request->name)) ? 'name' : 'email';
        return filter_var($this->userLogin, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
    }

    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), config('auth.rate_limiter.attempts'))) {
            return false;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $errors = ['warning' => ['message' => trans('throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60)
            ])
            ],
            'limiter' => ['message' => $seconds]
        ];
        return view('auth.login-form')->withErrors($errors)->render();
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->request->input('name')).'|'.$this->request->ip();
    }

}

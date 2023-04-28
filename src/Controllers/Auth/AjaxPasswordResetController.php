<?php

namespace PavelVasilyev\AuthAjax\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;

class AjaxPasswordResetController extends Controller
{
    // Импортируем трейт с методом respond (отправка ответа в браузер для вывода в модальном окне):
    use AjaxRespond;

    public function forgotPasswordForm()
    {
        $message = view('auth.forgot-password-form')->render();
        return $this->respond( true, 'Сброс пароля', $message );
    }

    public function forgotPasswordHandler($request, $email)
    {
        // Empty inputs check:
        $errors = [];
        if(empty($email)){
            $errors['email'] = ['message' => __('Enter the email specified during registration')];
            $old = $request->flash();
            $message = view('auth.forgot-password-form')->withErrors($errors)->withInput($old)->render();
            return $this->respond( false, 'Сброс пароля', $message );
        }

        // User check:
        $user = User::where('email', $email)->where('status', '1')->first();
        if(!$user){
            $errors = ['email' => ['message' => __('No user with such email address found')]];
            $old = $request->flash();
            $message = view('auth.forgot-password-form')->withErrors($errors)->withInput($old)->render();
            return $this->respond( false, 'Сброс пароля', $message );
        }
        // Reset-link sending:
        $status = Password::sendResetLink(
            $request->only('email')
        );

        If ($status == Password::RESET_LINK_SENT){
            $message = '<div class="alert alert-success">'.__('Link sent to your email').'</div>';
            return $this->respond( true, 'Сброс пароля', $message );
        } else {
            $old = $request->flash();
            $message = view('auth.forgot-password-form')->withErrors(['email' => __($status)])->withInput($old)->render();
            return $this->respond( false, 'Сброс пароля', $message );
        }
    }

    /**
     * Display the password reset view:
     */
    public function create(Request $request): \Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse
    {
        // 404 если:
        // - пользователь аутентифицирован:
        if (Auth::check()){ return redirect('/404'); }
        // - пользователь не существует:
        if (! $user = User::where('email',$request->email)->where('status','=','1')->first()){
            return redirect('/404');
        }
        // - в таблице сброса пароля не существует записи для пользователя с таким email:
        if (! $user = DB::table('password_resets')->where('email', $request->email)->first()){
            return redirect('/404');
        }
        // - не просрочен ли токен:
        $current = Carbon::now();
        $expire = Carbon::parse($user->created_at)->addMinutes(config('auth.passwords.users.expire'));
        if ($expire->diffInMinutes($current, false) > 0){
            return redirect('/404');
        }
        // - хэши токена в таблице сброса пароля и ссылке из email-уведомления не совпадают:
        if (! Hash::check($request->token, $user->token)){
            return redirect('/404');
        }
        // Если всё в порядке:
        // Уведомление - в сессию. При редиректе оно попадёт в код modal в представлении, js его увидит и запустит modal сразу после загрузки страницы:
        $message = view('auth.reset-password-form', ['request' => $request])->render();
        $request->session()->put('onload-modal', [
            'title' => 'Сброс пароля',
            'message' => $message
        ]);
        // Редирект на главную:
        return redirect('/');
    }

    /**
     * Handle an incoming new password request:
     */
    public function store(Request $request): JsonResponse
    {
        // Validation:
        $messages = [
            'password.required' => 'Введите новый пароль',
            'password.min' => 'Используйте в пароле не менее :min символов',
            'password.confirmed' => 'Введённые пароли не совпадают',
            'password_confirmation.required' => 'Повторите новый пароль',
        ];

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password_confirmation' => ['required'],
        ], $messages);

        if ($validator->fails()) {
            $old = $request->flash();
            $message = view('auth.reset-password-form', ['request' => $request])->withErrors($validator->errors())->withInput($old)->render();
            return $this->respond( false, 'Сброс пароля', $message );
        }

        // Save:
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET){
            $message = '<div class="text-center"><p class="alert alert-success">'.__('Lost password reset').'</p><button type="button" class="btn btn-primary btn-login-form">Войти</button></div>';
            return $this->respond( true, __('Login title'), $message );
        } else {
            Log::channel('notes')->info('ResetPassword. User: ' . $request->email . '. Error: ' . __($status));
            $message = '<div class="alert alert-danger">Ошибка приложения! <br />Пожалуйста, повторите позже.</div>';
            return $this->respond( false, 'Сброс пароля', $message );
        }
    }

}

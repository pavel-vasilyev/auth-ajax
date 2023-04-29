<?php

namespace PavelVasilyev\AuthAjax\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
//use App\Events\VerifyEmail;
use App\Notifications\VerifyEmailNotification;
//use App\Jobs\VerifyEmailJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class AjaxRegisterController extends Controller
{
    // Импортируем трейт с методом respond (отправка ответа в браузер для вывода в модальном окне):
    use AjaxRespond;

    public object $request;
    public object $user;
    protected $loginMin;
    protected $loginMax;
    protected $passwordMin;
    protected $passwordMax;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->loginMin = config('auth.login.min');
        $this->loginMax = config('auth.login.max');
        $this->passwordMin = config('auth.password.min');
        $this->passwordMax = config('auth.password.max');
    }

    public function handle()
    {
        switch ($this->request->input('action')) {
            case 'reg-form':
                return $this->formRegistration();
                break;
            case 'register':
                return $this->register();
                break;
            case 'new-link':
                $verifyEmail = new AjaxVerifyEmailController($this->request);
                return $verifyEmail->newVerifyLink();
                break;
            default:
                return $this->respond( false, 'Ошибка приложения', 'Не определён тип запроса' );
        }
    }

    // Вывод формы регистрации:
    public function formRegistration()
    {
        $message = view('auth.register-form')->render();
        return $this->respond( true, 'Регистрация на сайте', $message );
    }

    // Регистрация:
    public function register()
    {
        $messages = [
            'name.required' => 'Поле Логин обязательно к заполнению ',
            'name.alpha_dash' => 'Используйте только буквы, цифры, дефис и подчеркивание ',
            'name.regex' => 'Используйте только буквы, цифры, пробел, дефис и подчеркивание ',
            'name.min' => 'Используйте для логина не менее :min символов ',
            'name.max' => 'Используйте для логина не более :max символов ',
            'name.unique' => 'Этот логин уже занят ',
            'email.required' => 'Поле Email обязательно к заполнению ',
            'email.string' => 'Ошибка в поле Email',
            'email.email' => 'Неверный формат Email',
            'email.min' => 'Неверный формат Email',
            'email.max' => 'Используйте для Email не более :max символов ',
            'email.unique' => 'Укажите другой email',
            'password.required' => 'Укажите пароль ',
            'password.min' => 'Используйте для пароля не менее :min символов ',
            'password.max' => 'Используйте для пароля не более :max символов ',
        ];

        $validator = Validator::make($this->request->all(), [
            //'name' => ['required', 'alpha_dash', 'min:2', 'max:30', 'unique:users,name'], // alpha_dash не пропускает пробел
            'name' => [
                'required',
                'regex:/^[A-zА-яЁё\d]+[A-zА-яЁё\d\s_-]+$/u',
                'min:'.$this->loginMin,
                'max:'.$this->loginMax,
                'unique:users,name',
            ],
            'email' => ['required', 'string', 'email', 'min:7', 'max:255', 'unique:users,email'],
            'password' => ['required', Password::min($this->passwordMin), 'max:'.$this->passwordMax]
        ], $messages);

        if ($validator->fails()) {
            $old = $this->request->flash();
            $message = view('auth.register-form')->withErrors($validator->errors())->withInput($old)->render();
            return $this->respond( false, 'Регистрация на сайте', $message );
        }

        // Сохранение нового пользователя:
        $this->user = User::create([
            'name' => $this->request->name,
            'email' => $this->request->email,
            'password' => Hash::make($this->request->password),
            'verify_token' => Str::lower(Str::random(60)),
            'status' => User::STATUS_INACTIVE,
        ]);

        // Почтовое уведомление пользователю с ссылкой верификации email (3 способа):

        // обычная синхронная отправка уведомления:
        $this->user->notify(new VerifyEmailNotification($this->user));

        // уведомление через событие/слушатель:
        //VerifyEmail::dispatch($this->user);

        // - асинхронная отправка через очередь
        //      (предварительно в среде разработке запустить php artisan queue:work,
        //      а в производственной среде - Supervisor):
        //VerifyEmailJob::dispatch($this->user);

        // Отчёт пользователю в modal:
        $message = '<div class="alert alert-success text-center">'.__('Account successfully created but not yet activated').'</div><p>'.__('Need to confirm e-mail address').'</p>';
        return $this->respond( true, 'Регистрация на сайте', $message );
    }

}

<?php

namespace PavelVasilyev\AuthAjax\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\NewVerifyEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Mail;
//use App\Mail\Auth\AjaxVerifyEmail;

class AjaxVerifyEmailController extends Controller
{
    // Импортируем трейт с методом respond (отправка ответа в браузер для вывода в модальном окне):
    use AjaxRespond;

    public object $request;
    public array $user;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function verify($id, $token)
    {
        if (Auth::check()){ return redirect('/404'); }

        if (! $this->user = User::where('id',$id)->where('status','!=','-1')->first()){
            return redirect('/404');
        }

        if ($this->user->status == '1'){
            $title = 'Ошибка!';
            $message = '<div style="text-align:center"><p>'.__('Your account has already been activated').'</p><button type="button" class="btn btn-primary btn-login-form">Войти на сайт</button></div>';
        }
        elseif ($this->user->status == '0' && $this->user->verify_token !== null && $this->user->verify_token != $token) {
            $title = 'Ошибка!';
            $message = '<div style="text-align:center"><p>Ссылка не действительна!</p><button type="button" class="btn btn-primary new-verify-link" data-id="'.$this->user->id.'">Новая ссылка</button></div>';
        }
        else {
            // Активируем:
            $this->user->email_verified_at = now();
            $this->user->status = User::STATUS_ACTIVE;
            $this->user->verify_token = null;
            $this->user->save();
            $title = 'Регистрация завершена';
            $message = '<div style="text-align:center"><p style="color:green; font-weight: bold">Поздравляем!</p><p>Ваш аккаунт успешно активирован!</p><button type="button" class="btn btn-primary btn-login-form">Войти на сайт</button></div>';
        }

        // Уведомление - в сессию. При редиректе оно попадёт в код modal в представлении, js его увидит и запустит modal сразу после загрузки страницы:
        $this->request->session()->put('onload-modal', [
            'title' => $title,
            'message' => $message
        ]);

        $this->request->session()->regenerateToken();

        return redirect('/');
    }

    public function newVerifyLink()
    {
        //$this->user = User::find($this->request->input('id'));
        $this->user = User::where('id', $this->request->input('id'))->where('status', '0')->first();
        if (! $this->user){ return redirect('/404'); }
        $this->user->verify_token = Str::lower(Str::random(60));
        $this->user->save();

        // Email-уведомление с новой ссылкой:
        $this->user->notify(new NewVerifyEmailNotification($this->user));

        // Ответ в браузер:
        $message = '<p>На Ваш email, указанный при регистрации, выслано письмо с новой ссылкой для активации аккаунта. Пожалуйста, проверьте почтовый ящик!</p>';
        return $this->respond( true, 'Новая ссылка', $message);
    }
}

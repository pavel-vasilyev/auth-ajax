<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateAjax
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return string
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()){ // guest направляется на закрытую страницу:
            if ($request->session()->has('logout')){
                // если это user только что разлогинился, находясь на закрытой странице:
                $request->session()->forget('logout');
                return redirect('/'); // редирект в корень
            } else {
                // если это guest пытается войти на закрытую страницу:
                $message = '<div class="alert alert-danger text-center">'.__('access denied').'</div><div style="text-align:center"><button type="button" class="btn btn-primary btn-login-form" data-path="'.substr($request->fullUrl(), strlen(env('APP_URL'))+1).'">Войти</button></div></div>';
                session([
                    'onload-modal' => [
                        'title' => __('access denied title'),
                        'message' => $message,
                        ]
                    ]);

                return back(); // оставляем на исходной странице, выводим мод.окно
            }
        }

        // User аутентифицирован -> ему разрешается доступ к закрытой странице:
        return $next($request);
    }
}

<div id="header-auth">
@auth
    <span>{{ Auth::user()->name  }}</span>
    <button type="button" class="btn btn-link btn-logout">Выход</button>
@endauth
@guest
    <button type="button" class="btn btn-link text-dark btn-reg-form">Регистрация</button>
    <button type="button" class="btn btn-primary btn-login-form">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             width="16px" height="16px" viewBox="194.583 194.582 20 20" enable-background="new 194.583 194.582 20 20" xml:space="preserve">
                <g>
                    <g>
                        <path fill="#FFFFFF" d="M204.583,205.179c2.5,0,4.526-2.372,4.526-5.298c0-4.058-2.026-5.298-4.526-5.298
                            s-4.527,1.241-4.527,5.298C200.056,202.807,202.083,205.179,204.583,205.179z"/>
                        <path fill="#FFFFFF" d="M214.579,212.978l-2.284-5.146c-0.104-0.235-0.288-0.431-0.517-0.549l-3.544-1.846
                            c-0.078-0.04-0.173-0.032-0.242,0.021c-1.003,0.759-2.182,1.159-3.409,1.159s-2.406-0.4-3.408-1.159
                            c-0.071-0.053-0.166-0.061-0.243-0.021l-3.544,1.846c-0.229,0.118-0.412,0.313-0.516,0.549l-2.284,5.146
                            c-0.157,0.354-0.125,0.76,0.087,1.085c0.211,0.325,0.569,0.52,0.957,0.52h17.904c0.389,0,0.746-0.194,0.957-0.52
                            C214.704,213.737,214.736,213.331,214.579,212.978z"/>
                    </g>
                </g>
            </svg>
        Войти
    </button>
    @endguest
    </div>

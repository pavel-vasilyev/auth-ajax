<form id="loginForm" @isset($toPath)data-path="{{ $toPath }}"@endisset @error('limiter') class="formTimeout" data-remtime="{{ $message }}"@enderror>
    <div class="mb-3">
        <label for="name" class="form-label">{{ __('Login or email') }}</label>
        <div class="fakeInput form-control @error('name') is-invalid @enderror" data-name="name" contenteditable="" spellcheck="false">{{ old('name') }}</div>
        <input id="name" type="hidden" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" maxlength="{{ config('auth.login.max') }}">
        @error('name')
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <div class="fakeInput form-control @error('password') is-invalid @enderror" data-name="password" contenteditable="" spellcheck="false"></div>
        <input id="password" type="hidden" class="form-control @error('password') is-invalid @enderror" name="password" maxlength="60">
        @error('password')
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
        <div class="text-end"><button type="button" class="btn btn-link btn-sm forgot-password">{{ __('Forgot password') }}</button></div>
    </div>
    <div class="mb-3">
        <input id="remember_me" type="checkbox" class="form-check-input" name="remember" {{ old('remember') == 'on' ? 'checked' : '' }}>
        <label class="form-check-label" for="remember_me">Запомнить меня</label>
    </div>
    @error('warning')
    <div class="alert alert-danger text-center">{!! $message !!}</div>
    @enderror

    <div class="modal-footer justify-content-center">
        <button type="submit" class="btn btn-primary btn-login">Отправить</button>
    </div>
</form>
<div class="text-end"><button type="button" class="btn btn-link text-success btn-reg-form">Регистрация</button></div>

<form id="registerForm">
    <div class="mb-3">
        <label for="name" class="form-label">Логин</label>
        <div class="fakeInput form-control @error('name') is-invalid @enderror" data-name="name" contenteditable="" spellcheck="false">{{ old('name') }}</div>
        <input id="name" type="hidden" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" maxlength="{{ config('auth.login.max') }}">
        @error('name')
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <div class="fakeInput form-control @error('email') is-invalid @enderror" data-name="email" contenteditable="" spellcheck="false">{{ old('email') }}</div>
        <input id="email" type="hidden" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" maxlength="255">
        @error('email')
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Пароль</label>
        <div class="fakeInput form-control @error('password') is-invalid @enderror" data-name="password" contenteditable="" spellcheck="false"></div>
        <input id="password" type="hidden" class="form-control @error('password') is-invalid @enderror" name="password" maxlength="{{ config('auth.password.max') }}">
        @error('password')
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>
    <div class="modal-footer justify-content-center">
        <button type="submit" class="btn btn-primary">Отправить</button>
    </div>
</form>
<div class="text-end"><button type="button" class="btn btn-link btn-sm text-success btn-login-form">Уже зарегистрированы?</button></div>


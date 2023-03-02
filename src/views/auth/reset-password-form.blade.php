<form id="resetPassword">
    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ old('token', $request->route('token')) }}">

    <!-- Email Address -->
    <input type="hidden" name="email" value="{{ $request->email }}">

    <!-- Password -->
    <div class="mb-3">
        <label for="password" class="form-label">Новый пароль:</label>
        <div class="fakeInput form-control @error('password') is-invalid @enderror" data-name="password" contenteditable="" spellcheck="false"></div>
        <input id="password" type="hidden" class="form-control @error('password') is-invalid @enderror" name="password" maxlength="{{ config('auth.password.max') }}">
        @error('password')
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Повторите пароль:</label>
        <div class="fakeInput form-control @error('password_confirmation') is-invalid @enderror" data-name="password_confirmation" contenteditable="" spellcheck="false"></div>
        <input id="password_confirmation" type="hidden" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" maxlength="{{ config('auth.password.max') }}">
        @error('password_confirmation')
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>

    <!-- Submit -->
    <div class="modal-footer justify-content-center">
        <button type="submit" class="btn btn-primary">Отправить</button>
    </div>
</form>

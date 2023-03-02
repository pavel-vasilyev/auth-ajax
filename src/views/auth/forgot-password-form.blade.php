<form id="forgotPasswordForm">
    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Your email') }}:</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email">
        @error('email')
        <span class="invalid-feedback" role="alert">{{ $message }}</span>
        @enderror
    </div>
    @error('warning')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <div class="modal-footer justify-content-center">
        <button type="submit" class="btn btn-primary">Отправить</button>
    </div>
</form>



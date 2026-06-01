<section>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label fw-bold">{{ __('messages.current_password') }}</label>
            <input type="password" name="current_password" id="update_password_current_password" class="form-control" autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label fw-bold">{{ __('messages.new_password') }}</label>
            <input type="password" name="password" id="update_password_password" class="form-control" autocomplete="new-password">
            @error('password', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label fw-bold">{{ __('messages.confirm_password') }}</label>
            <input type="password" name="password_confirmation" id="update_password_password_confirmation" class="form-control" autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('messages.update_password') }}</button>

            @if (session('status') === 'password-updated')
                <span class="text-success small fade-out">
                    <i class="fas fa-check-circle me-1"></i> {{ __('messages.saved') }}
                </span>
            @endif
        </div>
    </form>
</section>

<section>
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label fw-bold">{{ __('messages.name') }}</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-bold">{{ __('messages.email') }}</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-muted small mb-1">{{ __('messages.email_unverified') }}</p>
                    <button form="send-verification" class="btn btn-link p-0 small">{{ __('messages.resend_verification_link') }}</button>
                    
                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2 py-2 small">
                            {{ __('messages.verification_link_sent_profile') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">{{ __('messages.save_changes') }}</button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small fade-out">
                    <i class="fas fa-check-circle me-1"></i> {{ __('messages.saved') }}
                </span>
            @endif
        </div>
    </form>
    
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
        @csrf
    </form>
</section>

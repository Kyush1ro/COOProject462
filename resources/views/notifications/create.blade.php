@extends('layouts.dashboard')

@section('title', __('messages.send_global_notice'))
@section('page-title', __('messages.send_global_notice'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-paper-plane me-2"></i> {{ __('messages.compose_notice') }}
                </div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('notifications.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.subject') }}</label>
                            <input type="text" name="subject" class="form-control" placeholder="{{ __('messages.important_announcement_placeholder') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.message') }}</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="{{ __('messages.write_message_placeholder') }}" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.recipients') }}</label>
                            <div class="card p-3 bg-body-tertiary" style="max-height: 300px; overflow-y: auto;">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                                    <label class="form-check-label fw-bold" for="selectAll">{{ __('messages.select_all_users') }}</label>
                                </div>
                                <hr>
                                @foreach($users as $user)
                                    <div class="form-check">
                                        <input class="form-check-input recipient-checkbox" type="checkbox" name="recipients[]" value="{{ $user->Academic_ID }}" id="user_{{ $user->Academic_ID }}">
                                        <label class="form-check-label" for="user_{{ $user->Academic_ID }}">
                                            {{ $user->name }} 
                                            <span class="badge bg-secondary ms-1">{{ ucfirst($user->role) }}</span>
                                            <small class="text-muted">({{ $user->email }})</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-text">{{ __('messages.select_recipient_help') }}</div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-primary text-white">
                                <i class="fas fa-paper-plane me-1"></i> {{ __('messages.send_notice') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSelectAll(source) {
            checkboxes = document.querySelectorAll('.recipient-checkbox');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
@endsection

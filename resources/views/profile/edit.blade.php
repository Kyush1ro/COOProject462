@extends('layouts.dashboard')

@section('title', __('messages.my_profile'))
@section('page-title', __('messages.my_profile'))

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        {{-- Profile Card --}}
        <div class="card shadow-sm text-center h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <div class="avatar avatar-xl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2 text-capitalize">{{ $user->role }}</p>
                <div class="badge bg-secondary text-white border">{{ $user->Academic_ID }}</div>
                
                <div class="mt-4 w-100 text-start">
                    <hr>
                    <div class="mb-2">
                        <i class="fas fa-envelope text-muted me-2"></i> {{ $user->email }}
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-calendar-alt text-muted me-2"></i> {{ __('messages.joined') }} {{ $user->created_at->format('M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        {{-- Update Profile Info --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3">
                <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> {{ __('messages.update_information') }}</h5>
            </div>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        {{-- Update Password --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3">
                <h5 class="mb-0"><i class="fas fa-lock me-2"></i> {{ __('messages.change_password') }}</h5>
            </div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.dashboard')

@section('title', __('messages.add_user'))
@section('page-title', __('messages.create_new_user'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-user-plus me-2"></i> {{ __('messages.user_details') }}
                </div>
                <div class="card-body">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        {{-- Role Selection --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.role') }}</label>
                            <select name="role" class="form-select" required>
                                <option value="student" selected>{{ __('messages.student') }}</option>
                                <option value="instructor">{{ __('messages.instructor') }}</option>
                                <option value="admin">{{ __('messages.admin') }}</option>
                            </select>
                        </div>

                        {{-- Academic ID --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.academic_id') }}</label>
                            <input type="number" name="Academic_ID" class="form-control" placeholder="e.g. 4311018"
                                value="{{ old('Academic_ID') }}" required>
                        </div>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.full_name') }}</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. John Doe"
                                value="{{ old('name') }}" required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.email_address') }}</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com"
                                value="{{ old('email') }}" required>
                        </div>

                        {{-- Password --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">{{ __('messages.password') }}</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">{{ __('messages.confirm_password') }}</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-success text-white">{{ __('messages.create_user') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

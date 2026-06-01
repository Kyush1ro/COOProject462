@extends('layouts.dashboard')

@section('title', __('messages.edit_user'))
@section('page-title', __('messages.edit_user') . ': ' . $user->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-edit me-2"></i> {{ __('messages.edit_details') }}
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

                    {{-- Note: We use the update route with the User ID --}}
                    <form action="{{ route('users.update', $user->Academic_ID) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Required for Updates --}}

                        {{-- Academic ID (Read Only) --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.academic_id') }}</label>
                            <input type="text" class="form-control bg-body-secondary" value="{{ $user->Academic_ID }}" readonly
                                disabled>
                            <div class="form-text">{{ __('messages.id_cannot_be_changed') }}</div>
                        </div>

                        {{-- Role --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.role') }}</label>
                            <select name="role" class="form-select" required>
                                <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>{{ __('messages.student') }}</option>
                                <option value="instructor" {{ $user->role == 'instructor' ? 'selected' : '' }}>{{ __('messages.instructor') }}
                                </option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>{{ __('messages.admin') }}</option>
                            </select>
                        </div>

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.full_name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.email_address') }}</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        <hr>
                        <h6 class="text-muted">{{ __('messages.change_password_optional') }}</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.new_password') }}</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="{{ __('messages.leave_blank_keep_current') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('messages.confirm_password') }}</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-warning">{{ __('messages.update_user') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

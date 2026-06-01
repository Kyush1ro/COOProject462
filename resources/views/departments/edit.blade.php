@extends('layouts.dashboard')

@section('title', __('messages.edit_department'))
@section('page-title', __('messages.edit_department'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">{{ __('messages.edit_details') }}</div>
                <div class="card-body">
                    <form action="{{ route('departments.update', $department->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.department_name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ $department->name }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.department_code') }}</label>
                            <input type="text" name="code" class="form-control" value="{{ $department->code }}"
                                required>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('departments.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-warning">{{ __('messages.update_user') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <i class="fas fa-book me-2"></i> {{ __('messages.courses_in_department', ['name' => $department->name]) }}
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($department->courses as $course)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <strong>{{ $course->course_code }}</strong>: {{ $course->title }}
                            </span>
                            <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.view') }}</a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center py-3">{{ __('messages.no_courses_assigned') }}</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.dashboard')

@section('title', __('messages.available_courses'))
@section('page-title', __('messages.course_catalog'))

{{-- FIX: Put the button in the 'page-actions' section and close it properly --}}
@section('page-actions')
    <a href="{{ route('courses.index') }}" class="btn btn-secondary text-white">
        <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back_to_my_courses') }}
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-globe me-2"></i> {{ __('messages.courses_available_to_join') }} ({{ \App\Models\Semester::getActive()->id ?? 'No Active Semester' }})
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('messages.code') }}</th>
                            <th>{{ __('messages.title') }}</th>
                            <th>{{ __('messages.instructor') }}</th>
                            <th>{{ __('messages.location') }}</th>
                            <th>{{ __('messages.type') }}</th>
                            <th class="text-center">{{ __('messages.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td class="fw-bold text-primary">{{ $course->course_code }}</td>
                                <td class="fw-bold">{{ $course->title }}</td>
                                <td>{{ $course->instructor->name }}</td>
                                <td>{{ $course->classroom }}</td>
                                <td>
                                    @if ($course->course_type == 'lab')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-laptop-code me-1"></i> {{ __('messages.lab') }}
                                        </span>
                                    @else
                                        <span class="badge bg-info text-white">
                                            <i class="fas fa-layer-group me-1"></i> {{ __('messages.theory') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                                        @csrf
                                        @if(\App\Models\Setting::getValue('enrollment_open') == '1')
                                            <button class="btn btn-sm btn-success text-white">
                                                <i class="fas fa-sign-in-alt me-1"></i> {{ __('messages.enroll_now') }}
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-secondary text-white" disabled>
                                                <i class="fas fa-lock me-1"></i> {{ __('messages.enrollment_closed') }}
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i><br>
                                    {{ __('messages.you_have_enrolled_all') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

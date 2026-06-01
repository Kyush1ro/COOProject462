@extends('layouts.dashboard')

@section('title', __('messages.my_students'))
@section('page-title', __('messages.student_roster'))

@section('content')
<div class="row">
    <div class="col-12">
        
        {{-- STATE 1: COURSE SELECTION --}}
        @if(!isset($selectedCourse))
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">{{ __('messages.select_course_students') }}</h4>
            </div>

            <div class="row">
                @forelse($courses as $course)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm hover-shadow transition-all">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title text-primary mb-0">{{ $course->title }}</h5>
                                    <span class="badge bg-secondary text-white border">{{ $course->course_code }}</span>
                                </div>
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($course->description, 80) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="text-muted small">
                                        <i class="fas fa-users me-1"></i> {{ $course->students->count() }} {{ __('messages.students_count') }}
                                    </span>
                                    <a href="{{ route('teacher.students.index', ['course_id' => $course->id]) }}" class="btn btn-outline-primary btn-sm stretched-link">
                                        {{ __('messages.view_roster') }} <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i> {{ __('messages.not_teaching_courses') }}
                        </div>
                    </div>
                @endforelse
            </div>

        {{-- STATE 2: STUDENT LIST FOR SELECTED COURSE --}}
        @else
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('teacher.students.index') }}" class="text-decoration-none text-muted small mb-1 d-block">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back_to_courses') }}
                    </a>
                    <h4 class="mb-0">
                        <span class="text-primary">{{ $selectedCourse->title }}</span> 
                        <span class="text-muted fw-light">{{ __('messages.students') }}</span>
                    </h4>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.name') }}</th>
                                    <th>{{ __('messages.academic_id') }}</th>
                                    <th>{{ __('messages.email') }}</th>
                                    <th>{{ __('messages.enrolled_on') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md bg-primary text-white me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $student->name }}</div>
                                                    <div class="small text-muted">{{ __('messages.student') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="font-monospace">{{ $student->Academic_ID }}</span></td>
                                        <td>
                                            <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                                {{ $student->email }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $student->pivot->created_at ? $student->pivot->created_at->format('M d, Y') : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            {{ __('messages.no_students_enrolled_yet') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endsection

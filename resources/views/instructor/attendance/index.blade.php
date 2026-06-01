@extends('layouts.dashboard')

@section('title', __('messages.attendance_management'))
@section('page-title', __('messages.attendance_management'))

@section('content')
<div class="row">
    <div class="col-12">
        
        {{-- STATE 1: COURSE SELECTION --}}
        @if(!isset($selectedCourse))
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">{{ __('messages.select_course_take_attendance') }}</h4>
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
                                        <i class="fas fa-users me-1"></i> {{ $course->students->count() }} Students
                                    </span>
                                    <a href="{{ route('teacher.attendance.index', ['course_id' => $course->id]) }}" class="btn btn-outline-primary btn-sm stretched-link">
                                        {{ __('messages.manage_attendance') }} <i class="fas fa-arrow-right ms-1"></i>
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

        {{-- STATE 2: ATTENDANCE FORM FOR SELECTED COURSE --}}
        @else
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('teacher.attendance.index') }}" class="text-decoration-none text-muted small mb-1 d-block">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back_to_courses') }}
                    </a>
                    <h4 class="mb-0">
                        <span class="text-primary">{{ $selectedCourse->title }}</span> 
                        <span class="text-muted fw-light">{{ __('messages.attendance') }}</span>
                    </h4>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <form action="{{ route('teacher.attendance.index') }}" method="GET" class="row g-3 align-items-center">
                        <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
                        <div class="col-auto">
                            <label for="date" class="col-form-label fw-bold">{{ __('messages.date') }}:</label>
                        </div>
                        <div class="col-auto">
                            <input type="date" id="date" name="date" class="form-control" value="{{ $selectedDate }}" onchange="this.form.submit()">
                        </div>
                        <div class="col-auto">
                            <span class="text-muted small fst-italic">{{ __('messages.select_date_view_modify') }}</span>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.attendance.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
                        <input type="hidden" name="date" value="{{ $selectedDate }}">

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 30%">{{ __('messages.student') }}</th>
                                        <th style="width: 40%">{{ __('messages.status') }}</th>
                                        <th style="width: 30%">{{ __('messages.notes') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $student)
                                        @php
                                            $record = $attendances->get($student->Academic_ID);
                                            $status = $record ? $record->status : 'present'; // Default to present
                                            $note = $record ? $record->notes : '';
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm bg-secondary text-white me-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                        {{ substr($student->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $student->name }}</div>
                                                        <div class="small text-muted">{{ $student->Academic_ID }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <input type="radio" class="btn-check" name="attendance[{{ $student->Academic_ID }}]" id="present_{{ $student->Academic_ID }}" value="present" {{ $status == 'present' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-success btn-sm" for="present_{{ $student->Academic_ID }}">{{ __('messages.present') }}</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $student->Academic_ID }}]" id="late_{{ $student->Academic_ID }}" value="late" {{ $status == 'late' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-warning btn-sm" for="late_{{ $student->Academic_ID }}">{{ __('messages.late') }}</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $student->Academic_ID }}]" id="absent_{{ $student->Academic_ID }}" value="absent" {{ $status == 'absent' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-danger btn-sm" for="absent_{{ $student->Academic_ID }}">{{ __('messages.absent') }}</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $student->Academic_ID }}]" id="excused_{{ $student->Academic_ID }}" value="excused" {{ $status == 'excused' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-info btn-sm" for="excused_{{ $student->Academic_ID }}">{{ __('messages.excused') }}</label>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" name="notes[{{ $student->Academic_ID }}]" class="form-control form-control-sm" placeholder="{{ __('messages.optional_note') }}" value="{{ $note }}">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                {{ __('messages.no_students_enrolled') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($students->count() > 0)
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> {{ __('messages.save_attendance') }}
                                </button>
                            </div>
                        @endif
                    </form>
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

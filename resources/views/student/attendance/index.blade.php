@extends('layouts.dashboard')

@section('title', __('messages.my_attendance'))
@section('page-title', __('messages.my_attendance'))

@section('content')
<div class="row">
    <div class="col-12">
        
        {{-- STATE 1: COURSE SELECTION --}}
        @if(!isset($selectedCourse))
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">{{ __('messages.select_course_attendance') }}</h4>
            </div>

            <div class="row">
                @forelse($enrolledCourses as $course)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm hover-shadow transition-all">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title text-primary mb-0">{{ $course->title }}</h5>
                                    <span class="badge bg-light text-dark border">{{ $course->course_code }}</span>
                                </div>
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($course->description, 80) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <a href="{{ route('student.attendance.index', ['course_id' => $course->id]) }}" class="btn btn-outline-primary btn-sm stretched-link">
                                        {{ __('messages.view_attendance') }} <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i> {{ __('messages.not_enrolled_courses') }}
                        </div>
                    </div>
                @endforelse
            </div>

        {{-- STATE 2: ATTENDANCE RECORDS FOR SELECTED COURSE --}}
        @else
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('student.attendance.index') }}" class="text-decoration-none text-muted small mb-1 d-block">
                        <i class="fas fa-arrow-left me-1"></i> Back to Courses
                    </a>
                    <h4 class="mb-0">
                        <span class="text-primary">{{ $selectedCourse->title }}</span> 
                        <span class="text-muted fw-light">{{ __('messages.attendance_record') }}</span>
                    </h4>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $stats['present'] }}</h3>
                            <small>{{ __('messages.present') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $stats['absent'] }}</h3>
                            <small>{{ __('messages.absent') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $stats['late'] }}</h3>
                            <small>{{ __('messages.late') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $stats['percentage'] }}%</h3>
                            <small>{{ __('messages.attendance_rate') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('messages.date') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.notes') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendanceRecords as $record)
                                    <tr>
                                        <td>
                                            {{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }}
                                            <span class="text-muted small">({{ \Carbon\Carbon::parse($record->date)->format('l') }})</span>
                                        </td>
                                        <td>
                                            @if($record->status == 'present')
                                                <span class="badge bg-success">{{ __('messages.present') }}</span>
                                            @elseif($record->status == 'absent')
                                                <span class="badge bg-danger">{{ __('messages.absent') }}</span>
                                            @elseif($record->status == 'late')
                                                <span class="badge bg-warning text-dark">{{ __('messages.late') }}</span>
                                            @elseif($record->status == 'excused')
                                                <span class="badge bg-info text-dark">{{ __('messages.excused') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($record->notes)
                                                <span class="text-muted fst-italic">{{ $record->notes }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            {{ __('messages.no_attendance_records') }}
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

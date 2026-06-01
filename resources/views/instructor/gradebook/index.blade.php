@extends('layouts.dashboard')

@section('title', __('messages.gradebook'))
@section('page-title', __('messages.gradebook'))

@section('content')
<div class="row">
    <div class="col-12">
        
        {{-- STATE 1: COURSE SELECTION LIST --}}
        @if(!isset($selectedCourse))
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">{{ __('messages.select_course_gradebook') }}</h4>
            </div>

            <div class="row">
                @forelse($courses as $course)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm hover-shadow transition-all">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title text-primary mb-0">{{ $course->title }}</h5>
                                    <span class="badge bg-secondary-subtle text-body-emphasis border">{{ $course->course_code }}</span>
                                </div>
                                <p class="card-text text-muted small mb-3">
                                    {{ Str::limit($course->description, 80) }}
                                </p>
                                <div class="d-flex justify-content-end align-items-center mt-auto">
                                    <a href="{{ route('instructor.gradebook.index', ['course_id' => $course->id]) }}" class="btn btn-outline-primary btn-sm stretched-link">
                                        {{ __('messages.open_gradebook') }} <i class="fas fa-table ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i> {{ __('messages.no_courses_found') }}
                        </div>
                    </div>
                @endforelse
            </div>

        {{-- STATE 2: GRADEBOOK MATRIX --}}
        @else
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('instructor.gradebook.index') }}" class="text-decoration-none text-muted small mb-1 d-block">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back_to_courses') }}
                    </a>
                    <h4 class="mb-0">
                        <span class="text-primary">{{ $selectedCourse->title }}</span> 
                        <span class="text-muted fw-light">{{ __('messages.gradebook') }}</span>
                    </h4>
                </div>
                <div>
                    <button class="btn btn-success btn-sm" onclick="window.print()">
                        <i class="fas fa-print me-1"></i> {{ __('messages.print_export_pdf') }}
                    </button>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0 align-middle text-center">
                            <thead>
                                <tr>
                                    <th class="text-start bg-body-secondary" style="min-width: 200px; position: sticky; left: 0; z-index: 10;">{{ __('messages.student_name') }}</th>
                                    @foreach($selectedCourse->assignments as $assignment)
                                        <th style="min-width: 100px;">
                                            <div class="small text-muted text-truncate" style="max-width: 150px;" title="{{ $assignment->title }}">
                                                {{ $assignment->title }}
                                            </div>
                                            <span class="badge bg-secondary rounded-pill">{{ $assignment->max_score }} pts</span>
                                        </th>
                                    @endforeach
                                    <th class="bg-body-secondary fw-bold" style="min-width: 100px;">{{ __('messages.total_percentage') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($selectedCourse->students as $student)
                                    @php
                                        $totalPointsEarned = 0;
                                        $totalMaxPoints = 0;
                                    @endphp
                                    <tr>
                                        <td class="text-start fw-bold bg-body" style="position: sticky; left: 0; z-index: 5;">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm bg-primary text-white me-2 rounded-circle d-flex justify-content-center align-items-center" style="width: 30px; height: 30px;">
                                                    {{ substr($student->name, 0, 1) }}
                                                </div>
                                                {{ $student->name }}
                                            </div>
                                        </td>
                                        
                                        @foreach($selectedCourse->assignments as $assignment)
                                            @php
                                                $submission = $gradeMatrix[$student->Academic_ID][$assignment->id] ?? null;
                                                $totalMaxPoints += $assignment->max_score;
                                                if ($submission && $submission->grade !== null) {
                                                    $totalPointsEarned += $submission->grade;
                                                }
                                            @endphp
                                            
                                            <td>
                                                @if($submission)
                                                    @if($submission->grade !== null)
                                                        <span class="fw-bold {{ $submission->grade < ($assignment->max_score * 0.6) ? 'text-danger' : 'text-success' }}">
                                                            {{ $submission->grade }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark" title="{{ __('messages.submitted_not_graded') }}">?</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>
                                        @endforeach

                                        {{-- TOTAL COLUMN --}}
                                        <td class="fw-bold bg-body-secondary">
                                            @if($totalMaxPoints > 0)
                                                @php $percentage = ($totalPointsEarned / $totalMaxPoints) * 100; @endphp
                                                <span class="{{ $percentage < 60 ? 'text-danger' : 'text-success' }}">
                                                    {{ round($percentage, 1) }}%
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $selectedCourse->assignments->count() + 2 }}" class="text-center py-5 text-muted">
                                            {{ __('messages.no_students_enrolled') }}
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
    /* Custom scrollbar for large tables */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1; 
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #888; 
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555; 
    }
</style>
@endsection

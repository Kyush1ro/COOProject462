@extends('layouts.dashboard')

@section('title', 'My Progress')
@section('page-title', 'Track Personal Progress')

@section('content')
<div class="card">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-chart-line me-2"></i> My Learning Progress
    </div>
    <div class="card-body">
        @if($courses->isEmpty())
            <div class="alert alert-info text-center">
                You are not enrolled in any courses yet. 
                <a href="{{ route('courses.index') }}" class="alert-link">Browse Courses</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40%">Course Name</th>
                            <th style="width: 20%">Instructor</th>
                            <th style="width: 30%">Progress</th>
                            <th style="width: 10%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($courses as $course)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $course->title }}</div>
                                    <div class="small text-muted">{{ $course->course_code }}</div>
                                </td>
                                <td>{{ $course->instructor->name ?? 'Unknown' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated 
                                                {{ $course->current_progress == 100 ? 'bg-success' : 'bg-primary' }}" 
                                                role="progressbar" 
                                                style="width: {{ $course->current_progress }}%;" 
                                                aria-valuenow="{{ $course->current_progress }}" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                {{ $course->current_progress }}%
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('courses.show', $course->id) }}" class="btn btn-sm btn-outline-primary">
                                        Continue
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
@extends('layouts.dashboard')

@section('title', __('messages.assignments'))
@section('page-title', __('messages.all_assignments'))

@section('content')
    <div class="row">
        <div class="col-12">
            
            {{-- STATE 1: COURSE SELECTION LIST --}}
            @if(!isset($selectedCourse))
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">{{ __('messages.select_course') }}</h4>
                    @if (Auth::user()->isInstructor())
                        <a href="{{ route('assignments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> {{ __('messages.new_assignment') }}
                        </a>
                    @endif
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
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="text-muted small">
                                            <i class="fas fa-tasks me-1"></i> {{ $course->assignments_count ?? $course->assignments->count() }} {{ __('messages.assignments') }}
                                        </span>
                                        <a href="{{ route('assignments.index', ['course_id' => $course->id]) }}" class="btn btn-outline-primary btn-sm stretched-link">
                                            {{ __('messages.view_assignments') }} <i class="fas fa-arrow-right ms-1"></i>
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

            {{-- STATE 2: ASSIGNMENTS LIST FOR SELECTED COURSE --}}
            @else
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('assignments.index') }}" class="text-decoration-none text-muted small mb-1 d-block">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back_to_courses') }}
                        </a>
                        <h4 class="mb-0">
                            <span class="text-primary">{{ $selectedCourse->title }}</span> 
                            <span class="text-muted fw-light">{{ __('messages.assignments') }}</span>
                        </h4>
                    </div>
                    @if (Auth::user()->isInstructor())
                        <a href="{{ route('assignments.create', ['course_id' => $selectedCourse->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> {{ __('messages.new_assignment') }}
                        </a>
                    @endif
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 40%;">{{ __('messages.title') }}</th>
                                        <th style="width: 25%;">{{ __('messages.due_date') }}</th>
                                        <th style="width: 15%;">{{ __('messages.max_score') }}</th>
                                        <th style="width: 20%;">{{ __('messages.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assignments as $assignment)
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $assignment->title }}</span>
                                                @if($assignment->description)
                                                    <div class="text-muted small text-truncate" style="max-width: 300px;">
                                                        {{ $assignment->description }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="far fa-calendar-alt text-muted me-2"></i>
                                                    {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y - h:i A') }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary-subtle text-body-emphasis border">{{ $assignment->max_score }} pts</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('assignments.show', $assignment->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    {{ __('messages.view_details') }}
                                                </a>
                                                @if(Auth::user()->isInstructor())
                                                    <a href="{{ route('submissions.index', ['course_id' => $selectedCourse->id, 'assignment_id' => $assignment->id]) }}"
                                                        class="btn btn-sm btn-outline-success ms-1">
                                                        {{ __('messages.view_submissions') }}
                                                    </a>
                                                    <form action="{{ route('assignments.destroy', $assignment->id) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('{{ __('messages.delete_assignment_confirm') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <div class="mb-2"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
                                                {{ __('messages.no_assignments_found') }}
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

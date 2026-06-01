@extends('layouts.dashboard')

@section('title', $assignment->title)
@section('page-title', __('messages.assignment_details'))

@section('content')
    <div class="row">
        {{-- LEFT COLUMN: Assignment Instructions --}}
        <div class="col-md-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-pencil-alt me-2"></i> {{ $assignment->title }}</span>
                    <span class="badge bg-dark text-white">{{ __('messages.max_score') }}: {{ $assignment->max_score }}</span>
                </div>
                <div class="card-body">
                    <h6 class="text-muted fw-bold">{{ __('messages.description') }}:</h6>
                    <p class="lead">{{ $assignment->description ?? __('messages.no_description_provided') }}</p>

                    <hr>
                    <div class="d-flex justify-content-between text-muted small">
                        <span><i class="fas fa-book me-1"></i> {{ __('messages.courses') }}: {{ $assignment->course->title }}</span>
                        <span><i class="far fa-clock me-1"></i> {{ __('messages.due') }}:
                            {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y - h:i A') }}</span>
                    </div>
                </div>
            </div>

            {{-- INSTRUCTOR VIEW: Link to Submissions --}}
            {{-- INSTRUCTOR VIEW: Link to Submissions --}}
            @if (Auth::user()->isInstructor())
                <div class="card shadow-sm mt-4">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1"><i class="fas fa-users me-2"></i> {{ __('messages.student_submissions') }}</h5>
                            <p class="text-muted mb-0 small">
                                {{ $submissions->count() }} {{ __('messages.submissions_received') }}
                            </p>
                        </div>
                        <a href="{{ route('submissions.index', ['course_id' => $assignment->course_id, 'assignment_id' => $assignment->id]) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-list me-1"></i> {{ __('messages.view_all_submissions') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- RIGHT COLUMN: Student Submission Box --}}
        <div class="col-md-4">
            @if (Auth::user()->isStudent())
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-upload me-2"></i> {{ __('messages.your_work') }}
                    </div>
                    <div class="card-body">

                        @if ($userSubmission)
                            {{-- ALREADY SUBMITTED --}}
                            <div class="alert alert-success text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                                <strong>{{ __('messages.submitted') }}</strong><br>
                                <small>{{ $userSubmission->created_at->format('M d, Y h:i A') }}</small>
                            </div>

                            <div class="mb-3 text-center">
                                <a href="{{ route('submissions.download', $userSubmission->id) }}"
                                    class="btn btn-outline-dark btn-sm">
                                    <i class="fas fa-file-pdf text-danger me-1"></i> {{ __('messages.view_my_file') }}
                                </a>
                            </div>

                            @if ($userSubmission->grade)
                                <hr>
                                <div class="text-center">
                                    <h5>{{ __('messages.grade') }}</h5>
                                    <span class="display-6 text-primary fw-bold">{{ $userSubmission->grade }}</span>
                                    <span class="text-muted">/ {{ $assignment->max_score }}</span>
                                    @if ($userSubmission->feedback || $userSubmission->feedback_file_path)
                                        <div class="alert alert-light mt-3 text-start small border">
                                            @if($userSubmission->feedback)
                                                <strong>{{ __('messages.feedback') }}:</strong><br>
                                                {{ $userSubmission->feedback }}
                                                <br>
                                            @endif
                                            
                                            @if($userSubmission->feedback_file_path)
                                                <div class="mt-2">
                                                    <strong>{{ __('messages.feedback_file') }}:</strong>
                                                    <a href="{{ route('submissions.feedback.download', $userSubmission->id) }}" class="text-decoration-none">
                                                        <i class="fas fa-file-download me-1"></i> {{ __('messages.download_feedback') }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-warning text-center small">
                                    {{ __('messages.waiting_for_grading') }}
                                </div>
                            @endif
                        @else
                            {{-- NOT SUBMITTED YET --}}
                            <form action="{{ route('submissions.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">

                                <div class="mb-3">
                                    <label for="file" class="form-label fw-bold">{{ __('messages.upload_file_pdf_doc') }}</label>
                                    <input type="file" name="submission_file" id="file" class="form-control"
                                        required>
                                </div>

                                <button type="submit" class="btn btn-success w-100 text-white">
                                    <i class="fas fa-paper-plane me-1"></i> {{ __('messages.submit') }}
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

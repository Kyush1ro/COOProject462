@extends('layouts.dashboard')

@section('title', $course->title)
@section('page-title', __('messages.course_details'))

@section('content')
    <div class="row">

        {{-- LEFT COLUMN --}}
        <div class="col-md-8">

            {{-- A. Course Description Card --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-info-circle me-2"></i> {{ __('messages.about_course') }}</span>
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-light text-primary fw-bold">
                            <i class="fas fa-edit"></i> {{ __('messages.edit') }}
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <h2 class="h4 mb-3">{{ $course->title }}</h2>
                    <p class="lead">{{ $course->description ?? 'No description provided.' }}</p>

                    <hr>

                    {{-- Details Grid --}}
                    <div class="row text-muted small">
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-barcode me-2"></i> {{ __('messages.code') }}: <strong>{{ $course->course_code }}</strong>
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-user me-2"></i> {{ __('messages.instructor') }}: <strong>{{ $course->instructor->name }}</strong>
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-building me-2"></i> {{ __('messages.department') }}:
                            <strong>{{ $course->department ? $course->department->name : 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-calendar-alt me-2"></i> {{ __('messages.semester') }}:
                            <strong>{{ $course->semester ? $course->semester->id : 'N/A' }}</strong>
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i> {{ __('messages.location') }}: <strong>{{ $course->classroom }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- B. Materials Section --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-file-pdf me-2 text-danger"></i> {{ __('messages.learning_materials') }}</span>

                    {{-- Instructor Upload Button --}}
                    @if (Auth::user()->isInstructor() && Auth::user()->Academic_ID == $course->instructor_id)
                        <button class="btn btn-sm btn-success text-white" data-coreui-toggle="modal"
                            data-coreui-target="#uploadModal">
                            <i class="fas fa-plus"></i> {{ __('messages.add_file') }}
                        </button>
                    @endif
                </div>

                <div class="list-group list-group-flush">
                    @forelse($course->materials as $material)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="far fa-file-alt text-muted me-2"></i>
                                <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank"
                                    class="text-decoration-none text-dark">
                                    {{ $material->title }}
                                </a>
                            </div>
                            <a href="{{ asset('storage/' . $material->file_path) }}" class="btn btn-sm btn-outline-primary"
                                download>
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted py-4">
                            No materials uploaded yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-md-4">

            {{-- C. Actions (Enroll/Drop) --}}
            @if (Auth::user()->isStudent())
                <div class="card mb-4 shadow-sm border-top-primary">
                    <div class="card-body text-center">
                        @if (Auth::user()->enrolledCourses->contains($course->id))
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-check-circle"></i> {{ __('messages.you_are_enrolled') }}
                            </div>
                            <form action="{{ route('courses.drop', $course->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger w-100">{{ __('messages.drop_course') }}</button>
                            </form>
                        @else
                            <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
                                @csrf
                                <button class="btn btn-success w-100 btn-lg text-white">{{ __('messages.enroll_now') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            {{-- D. Assignments --}}
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark fw-bold">
                    <i class="fas fa-tasks me-2"></i> {{ __('messages.assignments') }}
                    @if (Auth::user()->isInstructor() && Auth::user()->Academic_ID == $course->instructor_id)
                        <a href="{{ route('assignments.create') }}" class="btn btn-sm btn-dark text-white">
                            <i class="fas fa-plus"></i> {{ __('Add Assignment') }}
                        </a>
                    @endif
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($course->assignments as $assignment)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">{{ $assignment->title }}</div>
                                <small class="text-muted">{{ __('messages.due') }}:
                                    {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d') }}</small>
                            </div>
                            <a href="{{ route('assignments.show', $assignment->id) }}"
                                class="btn btn-sm btn-outline-dark">Go</a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted text-center small py-3">No assignments.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- Upload Modal --}}
    @if (Auth::user()->isInstructor() && Auth::user()->Academic_ID == $course->instructor_id)
        <div class="modal fade" id="uploadModal" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('courses.materials.store', $course->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('messages.upload_material') }}</h5>
                            <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('messages.title') }}</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('messages.file') }}</label>
                                <input type="file" name="file" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">{{ __('messages.close') }}</button>
                            <button type="submit" class="btn btn-primary text-white">{{ __('messages.upload') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

@endsection

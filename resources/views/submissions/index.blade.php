@extends('layouts.dashboard')

@section('title', __('messages.submissions'))
@section('page-title', __('messages.submissions'))

@section('content')
    <div class="row">
        <div class="col-12">

            {{-- STATE 1: COURSE SELECTION LIST --}}
            @if (!isset($selectedCourse))
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">{{ __('messages.select_course') }}</h4>
                </div>

                <div class="row">
                    @forelse($courses as $course)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm hover-shadow transition-all">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title text-primary mb-0">{{ $course->title }}</h5>
                                        <span class="badge bg-secondary-subtle text-body-emphasis border">{{ $course->course_code }}</span>
                                    </div>
                                    <p class="card-text text-muted small mb-3 flex-grow-1">
                                        {{ Str::limit($course->description, 80) }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <a href="{{ route('submissions.index', ['course_id' => $course->id]) }}"
                                            class="btn btn-outline-primary btn-sm w-100 stretched-link">
                                            {{ __('messages.view_submissions') }} <i class="fas fa-arrow-right ms-1"></i>
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

                {{-- STATE 2: SUBMISSIONS LIST FOR SELECTED COURSE --}}
            @else
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <a href="{{ route('submissions.index') }}"
                            class="text-decoration-none text-muted small mb-1 d-block">
                            <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back_to_courses') }}
                        </a>
                        <h4 class="mb-0">
                            <span class="text-primary">{{ $selectedCourse->title }}</span>
                            <span class="text-muted fw-light">{{ __('messages.submissions') }}</span>
                        </h4>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 25%;">{{ __('messages.assignments') }}</th>
                                        <th style="width: 20%;">{{ __('messages.student') }}</th>
                                        <th style="width: 15%;">{{ __('messages.submitted') }}</th>
                                        <th style="width: 10%;">{{ __('messages.file') }}</th>
                                        <th style="width: 15%;">{{ __('messages.grade') }}</th>
                                        <th style="width: 15%;">{{ __('messages.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $hasSubmissions = false; @endphp

                                    @foreach ($selectedCourse->assignments as $assignment)
                                        @foreach ($assignment->submissions as $submission)
                                            @php $hasSubmissions = true; @endphp
                                            <tr>
                                                <td>
                                                    <a href="{{ route('assignments.show', $assignment->id) }}"
                                                        class="fw-bold text-decoration-none text-body-emphasis">
                                                        {{ $assignment->title }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm bg-secondary text-white me-2 rounded-circle d-flex justify-content-center align-items-center"
                                                            style="width: 30px; height: 30px;">
                                                            {{ substr($submission->student->name, 0, 1) }}
                                                        </div>
                                                        {{ $submission->student->name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted" title="{{ $submission->created_at }}">
                                                        {{ $submission->created_at->diffForHumans() }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('submissions.download', $submission->id) }}"
                                                        class="btn btn-sm btn-light border" title="{{ __('messages.download_file') }}">
                                                        <i class="fas fa-file-download text-danger"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    @if ($submission->grade)
                                                        <span class="badge bg-success">{{ $submission->grade }} /
                                                            {{ $assignment->max_score }}</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">{{ __('messages.pending') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (Auth::user()->isInstructor())
                                                        <div class="d-flex gap-1">
                                                            {{-- Grade Button --}}
                                                            <button class="btn btn-sm btn-primary text-white"
                                                                data-coreui-toggle="modal"
                                                                data-coreui-target="#gradeModal{{ $submission->id }}">
                                                                {{ __('messages.grade') }}
                                                            </button>

                                                            {{-- Delete Button --}}
                                                            <form
                                                                action="{{ route('submissions.destroy', $submission->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('{{ __('messages.delete_submission_confirm') }}');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-danger text-white" title="{{ __('messages.delete') }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>

                                                        {{-- Grade Modal --}}
                                                        <div class="modal fade" id="gradeModal{{ $submission->id }}"
                                                            tabindex="-1">
                                                            <div class="modal-dialog">
                                                                <form
                                                                    action="{{ route('submissions.update', $submission->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">{{ __('messages.grade_submission') }}</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-coreui-dismiss="modal"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="mb-3">
                                                                                <label class="form-label">{{ __('messages.score') }} (Max:
                                                                                    {{ $assignment->max_score }})</label>
                                                                                <input type="number" name="grade"
                                                                                    class="form-control"
                                                                                    max="{{ $assignment->max_score }}"
                                                                                    step="0.01" required
                                                                                    value="{{ $submission->grade }}">
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label class="form-label">{{ __('messages.feedback') }}</label>
                                                                                <textarea name="feedback" class="form-control" rows="3">{{ $submission->feedback }}</textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-coreui-dismiss="modal">{{ __('messages.close') }}</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary text-white">{{ __('messages.save_grade') }}</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach

                                    @if (!$hasSubmissions)
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">
                                                <div class="mb-2"><i class="fas fa-inbox fa-2x text-gray-300"></i></div>
                                                {{ __('messages.no_submissions_found') }}
                                            </td>
                                        </tr>
                                    @endif
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
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .transition-all {
            transition: all 0.3s ease;
        }
    </style>
@endsection

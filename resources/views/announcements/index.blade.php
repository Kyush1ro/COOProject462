@extends('layouts.dashboard')

@section('title', __('messages.announcements'))
@section('page-title', __('messages.announcements'))

@section('content')
<div class="row">
    <div class="col-12">
        
        {{-- STATE 1: COURSE SELECTION LIST --}}
        @if(!isset($selectedCourse))
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">{{ __('messages.select_course_announcement') }}</h4>
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
                                        <i class="fas fa-bullhorn me-1"></i> 
                                        {{ $course->announcements_count ?? $course->announcements->count() }} {{ __('messages.announcements') }}
                                        @if(Auth::user()->isStudent() && isset($course->unread_announcements_count) && $course->unread_announcements_count > 0)
                                            <span class="badge bg-danger ms-1">{{ $course->unread_announcements_count }} New</span>
                                        @endif
                                    </span>
                                    <a href="{{ route('announcements.index', ['course_id' => $course->id]) }}" class="btn btn-outline-primary btn-sm stretched-link">
                                        {{ __('messages.view_announcements') }} <i class="fas fa-arrow-right ms-1"></i>
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

        {{-- STATE 2: ANNOUNCEMENTS LIST FOR SELECTED COURSE --}}
        @else
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('announcements.index') }}" class="text-decoration-none text-muted small mb-1 d-block">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('messages.back_to_courses') }}
                    </a>
                    <h4 class="mb-0">
                        <span class="text-primary">{{ $selectedCourse->title }}</span> 
                        <span class="text-muted fw-light">{{ __('messages.announcements') }}</span>
                    </h4>
                </div>
                @if (Auth::user()->isInstructor())
                    <button class="btn btn-primary" data-coreui-toggle="modal" data-coreui-target="#createAnnouncementModal">
                        <i class="fas fa-plus me-1"></i> {{ __('messages.new_announcement') }}
                    </button>

                    {{-- Create Announcement Modal --}}
                    <div class="modal fade" id="createAnnouncementModal" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('announcements.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
                                <div class="modal-content bg-body-tertiary">
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ __('messages.post_announcement') }}</h5>
                                        <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.title') }}</label>
                                            <input type="text" name="title" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('messages.content') }}</label>
                                            <textarea name="content" class="form-control" rows="5" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">{{ __('messages.cancel') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ __('messages.post') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-12">
                    @forelse($announcements as $announcement)
                        <div class="card shadow-sm mb-3 bg-body-tertiary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <h5 class="card-title text-primary mb-1">{{ $announcement->title }}</h5>
                                    @if(Auth::user()->isInstructor())
                                        <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.are_you_sure') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <h6 class="card-subtitle mb-3 text-muted small">
                                    {{ __('messages.posted') }} {{ $announcement->created_at->diffForHumans() }}
                                </h6>
                                <p class="card-text" style="white-space: pre-wrap;">{{ $announcement->content }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center border py-5 rounded bg-body-tertiary">
                            <div class="mb-2"><i class="fas fa-bullhorn fa-2x text-muted"></i></div>
                            <p class="mb-0 text-muted">{{ __('messages.no_announcements_posted') }}</p>
                        </div>
                    @endforelse
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

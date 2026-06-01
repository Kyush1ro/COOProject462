@extends('layouts.dashboard')

@section('title', __('messages.courses_list'))
@section('page-title', __('messages.courses'))

{{-- Action Button: Add Course (Only for Admin/Instructor) --}}
@section('page-actions')
    @if (Auth::user()->isAdmin())
        <a href="{{ route('courses.create') }}" class="btn btn-primary text-white">
            <i class="fas fa-plus"></i> {{ __('messages.add_new_course') }}
        </a>
    @endif
    {{-- Button for Student --}}
    @if (Auth::user()->isStudent())
        <a href="{{ route('courses.available') }}" class="btn btn-success text-white">
            <i class="fas fa-plus-circle"></i> {{ __('messages.register_new_course') }}
        </a>
    @endif
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-book-open me-2"></i> {{ Auth::user()->isAdmin() ? __('messages.all_courses') : __('messages.my_courses') }}
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('messages.code') }}</th>
                            <th>{{ __('messages.title') }}</th>
                            <th>{{ __('messages.instructor') }}</th>
                            <th>{{ __('messages.department') }}</th>
                            <th>{{ __('messages.location') }}</th>
                            <th>{{ __('messages.type') }}</th>
                            <th class="text-center">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                {{-- 1. Course Code --}}
                                <td class="fw-bold text-primary">
                                    {{ $course->course_code }}
                                </td>

                                {{-- 2. Title --}}
                                <td class="fw-bold">
                                    {{ $course->title }}
                                </td>

                                {{-- 3. Instructor Name --}}
                                <td>
                                    <i class="fas fa-user-tie text-muted me-1"></i>
                                    {{ $course->instructor->name }}
                                </td>
                                {{-- 4. Department Name --}}
                                <td>
                                    @if($course->department)
                                        <span class="badge bg-secondary">{{ $course->department->code }}</span>
                                        <small class="d-block text-muted">{{ $course->department->name }}</small>
                                    @else
                                        <span class="text-muted fst-italic">None</span>
                                    @endif
                                    @if($course->semester)
                                        <span class="badge bg-info text-dark mt-1">{{ $course->semester->id }}</span>
                                    @endif
                                </td>

                                {{-- 5. Classroom (New) --}}
                                <td>
                                    {{ $course->classroom }}
                                </td>

                                {{-- 6. Course Type (New Badge Logic) --}}
                                <td>
                                    @if ($course->course_type == 'lab')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-laptop-code me-1"></i> {{ __('messages.lab') }}
                                        </span>
                                    @else
                                        <span class="badge bg-info text-white">
                                            <i class="fas fa-layer-group me-1"></i> {{ __('messages.theory') }}
                                        </span>
                                    @endif
                                </td>

                                {{-- 7. Actions --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        {{-- View Button --}}
                                        <a href="{{ route('courses.show', $course->id) }}"
                                            class="btn btn-sm btn-primary text-white">
                                            {{ __('messages.view') }}
                                        </a>

                                        {{-- Edit Button (Admin Only) --}}
                                        @if (Auth::user()->isAdmin())
                                            <a href="{{ route('courses.edit', $course->id) }}"
                                                class="btn btn-sm btn-warning text-dark">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        {{-- Drop Button (Student Only) --}}
                                        @if (Auth::user()->isStudent())
                                            <form action="{{ route('courses.drop', $course->id) }}" method="POST"
                                                onsubmit="return confirm('Drop this course?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>

                                            {{-- Enroll Button (If NOT Enrolled) --}}
                                        @endif

                                        {{-- Delete Button (Admin Only) --}}
                                        @if (Auth::user()->isAdmin())
                                            <form action="{{ route('courses.destroy', $course->id) }}" method="POST"
                                                onsubmit="return confirm('Delete this course permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger text-white">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                                    {{ __('messages.no_courses_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

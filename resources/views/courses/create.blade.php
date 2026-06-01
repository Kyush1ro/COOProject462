@extends('layouts.dashboard')

@section('title', __('messages.create_course'))
@section('page-title', __('messages.create_new_course'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-plus-circle me-2"></i> {{ __('messages.course_details') }}
                </div>
                <div class="card-body">

                    {{-- Display Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('courses.store') }}" method="POST">
                        @csrf

                        {{-- Row 1: Title & Code --}}
                        {{-- Row 1: Title & Code --}}
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="department_id" class="form-label fw-bold">{{ __('messages.department') }}</label>
                                <select name="department_id" id="department_id" class="form-select" required onchange="updateCourseCodePrefix()">
                                    <option value="" disabled selected>{{ __('messages.select_department') }}</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" data-code="{{ $dept->code }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }} ({{ $dept->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label for="title" class="form-label fw-bold">{{ __('messages.course_title') }}</label>
                                <input type="text" name="title" id="title" class="form-control"
                                    placeholder="e.g. Advanced Laravel" value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="course_code" class="form-label fw-bold">{{ __('messages.course_code') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="code_prefix">--</span>
                                    <input type="text" name="course_code" id="course_code" class="form-control"
                                        placeholder="101" value="{{ old('course_code') }}" required>
                                </div>
                                <small class="text-muted">Enter number only (e.g. 101)</small>
                            </div>
                        </div>

                        <script>
                            function updateCourseCodePrefix() {
                                const select = document.getElementById('department_id');
                                const prefixSpan = document.getElementById('code_prefix');
                                const selectedOption = select.options[select.selectedIndex];
                                const code = selectedOption.getAttribute('data-code');
                                
                                if (code) {
                                    prefixSpan.textContent = code;
                                } else {
                                    prefixSpan.textContent = '--';
                                }
                            }
                            
                            // Run on load in case of validation error redirect
                            document.addEventListener('DOMContentLoaded', function() {
                                updateCourseCodePrefix();
                            });
                        </script>

                        {{-- Row 2: Classroom & Type (NEW FIELDS) --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="classroom" class="form-label fw-bold">{{ __('messages.classroom_location') }}</label>
                                <select name="classroom" id="classroom" class="form-select" required>
                                    <option value="" disabled selected>{{ __('messages.select_classroom') }}</option>
                                    @foreach($classrooms as $id => $name)
                                        <option value="{{ $id }}" {{ old('classroom') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="course_type" class="form-label fw-bold">{{ __('messages.course_type') }}</label>
                                <select name="course_type" id="course_type" class="form-select" required>
                                    <option value="" disabled selected>{{ __('messages.select_type') }}</option>
                                    <option value="theory" {{ old('course_type') == 'theory' ? 'selected' : '' }}>{{ __('messages.theory') }}
                                    </option>
                                    <option value="lab" {{ old('course_type') == 'lab' ? 'selected' : '' }}>{{ __('messages.lab') }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 3: Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">{{ __('messages.description') }}</label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                placeholder="Brief summary of the course...">{{ old('description') }}</textarea>
                        </div>

                        {{-- Row 4: Assign Instructor (Dropdown) Only for Admin --}}
                        @if (Auth::user()->isAdmin())
                            <div class="mb-4">
                                <label for="instructor_id" class="form-label fw-bold text-danger">{{ __('messages.assign_instructor') }}</label>

                                <select name="instructor_id" id="instructor_id" class="form-select border-danger" required>
                                    <option value="" disabled selected>{{ __('messages.select_instructor') }}</option>

                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->Academic_ID }}"
                                            {{ old('instructor_id') == $instructor->Academic_ID ? 'selected' : '' }}>
                                            {{ $instructor->name }} (ID: {{ $instructor->Academic_ID }})
                                        </option>
                                    @endforeach
                                </select>

                                <div class="form-text">Select the professor who will teach this course.</div>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-success text-white">
                                <i class="fas fa-save me-1"></i> {{ __('messages.save_course') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

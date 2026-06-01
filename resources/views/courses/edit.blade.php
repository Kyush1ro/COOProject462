@extends('layouts.dashboard')

@section('title', __('messages.edit_course'))
@section('page-title', __('messages.edit_course'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-edit me-2"></i> {{ __('messages.edit_course_details') }}
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

                    <form action="{{ route('courses.update', $course->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Row 1: Department & Title --}}
                        <div class="row mb-3">
                            <div class="col-md-12 mb-3">
                                <label for="department_id" class="form-label fw-bold">{{ __('messages.department') }}</label>
                                <select name="department_id" id="department_id" class="form-select" required onchange="updateCourseCodePrefix()">
                                    <option value="" disabled>{{ __('messages.select_department') }}</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" data-code="{{ $dept->code }}" 
                                            {{ (old('department_id') ?? $course->department_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }} ({{ $dept->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-8">
                                <label for="title" class="form-label fw-bold">{{ __('messages.course_title') }}</label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="{{ old('title', $course->title) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label for="course_code" class="form-label fw-bold">{{ __('messages.course_code') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="code_prefix">--</span>
                                    {{-- We need to strip the prefix from the existing code for display if possible, 
                                         or just let the user edit the whole thing but we enforce the prefix logic.
                                         Actually, since we store the full code "CS101", we should probably 
                                         try to split it or just show it. 
                                         But the user wants the prefix logic. 
                                         Let's try to extract the number part if it matches the department prefix.
                                    --}}
                                    @php
                                        $currentCode = old('course_code', $course->course_code);
                                        // Simple logic: if we have a department, try to strip its code from the start
                                        $deptCode = $course->department ? $course->department->code : '';
                                        $displayCode = $currentCode;
                                        if ($deptCode && str_starts_with($currentCode, $deptCode)) {
                                            $displayCode = substr($currentCode, strlen($deptCode));
                                        }
                                    @endphp
                                    <input type="text" name="course_code" id="course_code" class="form-control"
                                        value="{{ $displayCode }}" required>
                                </div>
                                <small class="text-muted">{{ __('messages.enter_number_only') }}</small>
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
                            
                            document.addEventListener('DOMContentLoaded', function() {
                                updateCourseCodePrefix();
                            });
                        </script>

                        {{-- Row 2: Classroom & Type --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="classroom" class="form-label fw-bold">{{ __('messages.classroom_location') }}</label>
                                <select name="classroom" id="classroom" class="form-select" required>
                                    <option value="" disabled>{{ __('messages.select_classroom') }}</option>
                                    {{-- Add current classroom if it's not in the 'available' list (because it's taken by THIS course) --}}
                                    @if($course->classroom && $course->classroom()->exists())
                                         <option value="{{ $course->classroom()->first()->id }}" selected>
                                            {{ $course->classroom }} ({{ __('messages.current') }})
                                         </option>
                                    @elseif($course->classroom)
                                        {{-- Legacy support: if name exists but no relation --}}
                                        <option value="legacy" disabled selected>{{ $course->classroom }} (Legacy)</option>
                                    @endif

                                    @foreach($classrooms as $id => $name)
                                        <option value="{{ $id }}" {{ (old('classroom') == $id) ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="course_type" class="form-label fw-bold">{{ __('messages.course_type') }}</label>
                                <select name="course_type" id="course_type" class="form-select" required>
                                    <option value="theory" {{ (old('course_type', $course->course_type) == 'theory') ? 'selected' : '' }}>{{ __('messages.theory') }}</option>
                                    <option value="lab" {{ (old('course_type', $course->course_type) == 'lab') ? 'selected' : '' }}>{{ __('messages.lab') }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Row 3: Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">{{ __('messages.description') }}</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $course->description) }}</textarea>
                        </div>

                        {{-- Row 4: Instructor --}}
                        @if (Auth::user()->isAdmin())
                            <div class="mb-4">
                                <label for="instructor_id" class="form-label fw-bold text-danger">{{ __('messages.assign_instructor') }}</label>
                                <select name="instructor_id" id="instructor_id" class="form-select border-danger" required>
                                    <option value="" disabled>{{ __('messages.select_instructor') }}</option>
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->Academic_ID }}"
                                            {{ (old('instructor_id', $course->instructor_id) == $instructor->Academic_ID) ? 'selected' : '' }}>
                                            {{ $instructor->name }} (ID: {{ $instructor->Academic_ID }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('courses.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-primary text-white">
                                <i class="fas fa-save me-1"></i> {{ __('messages.update_course') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            

            
        </div>
    </div>
@endsection

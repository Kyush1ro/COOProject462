@extends('layouts.dashboard')

@section('title', __('messages.create_assignment'))
@section('page-title', __('messages.create_new_assignment'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-plus-circle me-2"></i> {{ __('messages.assignment_details') }}
            </div>
            <div class="card-body">
                
                <form action="{{ route('assignments.store') }}" method="POST">
                    @csrf

                    {{-- 1. Select Course --}}
                    <div class="mb-3">
                        <label for="course_id" class="form-label fw-bold">{{ __('messages.select_course') }}</label>
                        <select name="course_id" id="course_id" class="form-select" required>
                            <option value="" disabled selected>{{ __('messages.choose_course') }}</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->course_code }} - {{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Title --}}
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">{{ __('messages.assignment_title') }}</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="e.g. Midterm Project" required>
                    </div>

                    {{-- 3. Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">{{ __('messages.instructions_description') }}</label>
                        <textarea name="description" id="description" class="form-control" rows="4" placeholder="{{ __('messages.explain_student_needs') }}"></textarea>
                    </div>

                    {{-- 4. Due Date & Score --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label fw-bold">{{ __('messages.due_date') }}</label>
                            <input type="datetime-local" name="due_date" id="due_date" class="form-control" min="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="max_score" class="form-label fw-bold">{{ __('messages.max_score') }}</label>
                            <input type="number" name="max_score" id="max_score" class="form-control" value="100" min="1" required>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('assignments.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                        <button type="submit" class="btn btn-success text-white">
                            <i class="fas fa-save me-1"></i> {{ __('messages.create_assignment') }}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
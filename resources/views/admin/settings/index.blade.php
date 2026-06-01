@extends('layouts.dashboard')

@section('page-title', __('messages.academic_settings'))

@section('breadcrumb')
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('messages.home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('messages.settings') }}</li>
    </ol>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('messages.general_settings') }}
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.store') }}" method="POST">
                @csrf
                
                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="registration_open" name="registration_open" 
                        {{ \App\Models\Setting::getValue('registration_open') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="registration_open">{{ __('messages.open_student_registration') }}</label>
                    <div class="form-text">{{ __('messages.registration_help') }}</div>
                </div>

                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="enrollment_open" name="enrollment_open" 
                        {{ \App\Models\Setting::getValue('enrollment_open') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="enrollment_open">{{ __('messages.open_course_enrollment') }}</label>
                    <div class="form-text">{{ __('messages.enrollment_help') }}</div>
                </div>

                <div class="mb-3">
                    <label for="current_semester" class="form-label">{{ __('messages.current_semester') }}</label>
                    <select class="form-select" id="current_semester" name="current_semester">
                        <option value="">{{ __('messages.select_semester') }}</option>
                        @php
                            $savedSemester = \App\Models\Setting::getValue('current_semester');
                            $activeSemester = \App\Models\Semester::where('is_active', true)->first();
                            $selectedId = $savedSemester ?: ($activeSemester ? $activeSemester->id : null);
                        @endphp
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" 
                                {{ $selectedId == $semester->id ? 'selected' : '' }}>
                                {{ $semester->id }} ({{ $semester->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="drop_deadline" class="form-label">{{ __('messages.course_drop_deadline') }}</label>
                    <input type="date" class="form-control" id="drop_deadline" name="drop_deadline"
                        value="{{ \App\Models\Setting::getValue('drop_deadline') }}">
                    <div class="form-text">{{ __('messages.drop_deadline_help') }}</div>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('messages.save_settings') }}</button>
            </form>
        </div>
    </div>
@endsection

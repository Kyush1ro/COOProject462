@extends('layouts.dashboard')

@section('title', __('messages.create_semester'))
@section('page-title', __('messages.create_new_semester'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-plus-circle me-2"></i> {{ __('messages.semester_details') }}
                </div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('semesters.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.semester_id') }} ({{ __('messages.semester_id_placeholder') }})</label>
                            <input type="number" name="id" class="form-control" placeholder="{{ __('messages.semester_id_placeholder') }}" required>
                            <div class="form-text">{{ __('messages.semester_id_help') }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.name') }}</label>
                            <input type="text" name="name" class="form-control" placeholder="{{ __('messages.semester_name_placeholder') }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.start_date') }}</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.end_date') }}</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1">
                            <label class="form-check-label fw-bold" for="is_active">{{ __('messages.set_active_semester') }}</label>
                            <div class="form-text text-warning">{{ __('messages.set_active_help') }}</div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('semesters.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-success text-white">{{ __('messages.create_semester') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

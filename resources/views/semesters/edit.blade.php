@extends('layouts.dashboard')

@section('title', __('messages.edit_semester'))
@section('page-title', __('messages.edit_semester'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <i class="fas fa-edit me-2"></i> {{ __('messages.edit_semester') }}: {{ $semester->name }}
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

                    <form action="{{ route('semesters.update', $semester->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.semester_id') }}</label>
                            <input type="text" class="form-control" value="{{ $semester->id }}" disabled>
                            <div class="form-text">{{ __('messages.id_cannot_be_changed') }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ $semester->name }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.start_date') }}</label>
                                <input type="date" name="start_date" class="form-control" value="{{ $semester->start_date->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.end_date') }}</label>
                                <input type="date" name="end_date" class="form-control" value="{{ $semester->end_date->format('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ $semester->is_active ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_active">{{ __('messages.set_active_semester') }}</label>
                            <div class="form-text text-warning">{{ __('messages.set_active_help') }}</div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('semesters.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-warning">{{ __('messages.update_semester') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

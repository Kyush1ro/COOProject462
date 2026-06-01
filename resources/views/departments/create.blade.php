@extends('layouts.dashboard')

@section('title', __('messages.add_department'))
@section('page-title', __('messages.add_new_department'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">{{ __('messages.department_details') }}</div>
                <div class="card-body">
                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.department_name') }}</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Computer Science"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('messages.department_code') }}</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. CS" required>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('departments.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                            <button type="submit" class="btn btn-success text-white">{{ __('messages.create') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

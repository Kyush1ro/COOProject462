@extends('layouts.dashboard')

@section('title', 'Add Equipment')
@section('page-title', 'Add Equipment')

@section('page-actions')
    <a href="{{ route('equipment.index') }}" class="btn btn-secondary text-white">
        <i class="fas fa-arrow-left"></i> Back
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-plus me-2"></i> New Equipment
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

            <form method="POST" action="{{ route('equipment.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Equipment Code</label>
                    <input type="text" name="equipment_code" class="form-control"
                           value="{{ old('equipment_code') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Equipment Name</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <input type="text" name="type" class="form-control"
                           value="{{ old('type') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control"
                           value="{{ old('location') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active">Active</option>
                        <option value="under_maintenance">Under Maintenance</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary text-white">
                    <i class="fas fa-save"></i> Save
                </button>
            </form>
        </div>
    </div>
@endsection
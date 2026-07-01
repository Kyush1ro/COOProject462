@extends('layouts.dashboard')

@section('title', 'Add Spare Part')
@section('page-title', 'Add Spare Part')

@section('page-actions')
    <a href="{{ route('spare-parts.index') }}" class="btn btn-secondary text-white">
        <i class="fas fa-arrow-left"></i> Back
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-plus me-2"></i> New Spare Part
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

            <form method="POST" action="{{ route('spare-parts.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Part Code</label>
                    <input type="text" name="part_code" class="form-control" value="{{ old('part_code') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Part Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 0) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Minimum Quantity</label>
                    <input type="number" name="minimum_quantity" class="form-control" value="{{ old('minimum_quantity', 0) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Unit</label>
                    <input type="text" name="unit" class="form-control" value="{{ old('unit', 'pcs') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Storage Location</label>
                    <input type="text" name="storage_location" class="form-control" value="{{ old('storage_location') }}">
                </div>

                <button type="submit" class="btn btn-primary text-white">
                    <i class="fas fa-save"></i> Save
                </button>
            </form>
        </div>
    </div>
@endsection
@extends('layouts.dashboard')

@section('title', 'Add Purchase Requisition')
@section('page-title', 'Add Purchase Requisition')

@section('page-actions')
    <a href="{{ route('purchase-requisitions.index') }}" class="btn btn-secondary text-white">
        <i class="fas fa-arrow-left"></i> Back
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-plus me-2"></i> New Purchase Requisition
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

            <form method="POST" action="{{ route('purchase-requisitions.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">PR Number</label>
                    <input type="text" name="pr_number" class="form-control"
                           value="{{ old('pr_number') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Related Spare Part</label>
                    <select name="spare_part_id" class="form-select">
                        <option value="">No related spare part</option>
                        @foreach ($spareParts as $part)
                            <option value="{{ $part->id }}">
                                {{ $part->part_code }} - {{ $part->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Item Name</label>
                    <input type="text" name="item_name" class="form-control"
                           value="{{ old('item_name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control"
                           value="{{ old('quantity', 1) }}" min="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Unit</label>
                    <input type="text" name="unit" class="form-control"
                           value="{{ old('unit', 'pcs') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" selected>Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="ordered">Ordered</option>
                        <option value="received">Received</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Required Date</label>
                    <input type="date" name="required_date" class="form-control"
                           value="{{ old('required_date') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Justification</label>
                    <textarea name="justification" class="form-control" rows="3">{{ old('justification') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary text-white">
                    <i class="fas fa-save"></i> Save
                </button>
            </form>
        </div>
    </div>
@endsection
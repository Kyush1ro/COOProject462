@extends('layouts.dashboard')

@section('title', 'Add Work Order')
@section('page-title', 'Add Work Order')

@section('page-actions')
    <a href="{{ route('work-orders.index') }}" class="btn btn-secondary text-white">
        <i class="fas fa-arrow-left"></i> Back
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-plus me-2"></i> New Work Order
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

            <form method="POST" action="{{ route('work-orders.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Work Order Number</label>
                    <input type="text" name="work_order_number" class="form-control"
                           value="{{ old('work_order_number') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Related Maintenance Request</label>
                    <select name="maintenance_request_id" class="form-select">
                        <option value="">No related request</option>
                        @foreach ($maintenanceRequests as $request)
                            <option value="{{ $request->id }}">
                                {{ $request->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Equipment</label>
                    <select name="equipment_id" class="form-select" required>
                        <option value="">Select Equipment</option>
                        @foreach ($equipment as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->equipment_code }} - {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Assigned To</label>
                    <select name="assigned_to" class="form-select">
                        <option value="">Unassigned</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} - {{ $user->role }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="open" selected>Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Planned Start Date</label>
                    <input type="date" name="planned_start_date" class="form-control"
                           value="{{ old('planned_start_date') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Planned End Date</label>
                    <input type="date" name="planned_end_date" class="form-control"
                           value="{{ old('planned_end_date') }}">
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
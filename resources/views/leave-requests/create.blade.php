@extends('layouts.dashboard')

@section('title', 'Add Leave Request')
@section('page-title', 'Add Leave Request')

@section('page-actions')
    <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary text-white">
        <i class="fas fa-arrow-left"></i> Back
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-plus me-2"></i> New Leave Request
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

            <form method="POST" action="{{ route('leave-requests.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Employee</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">Select Employee</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} - {{ $user->role }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Leave Type</label>
                    <select name="leave_type" class="form-select" required>
                        <option value="annual">Annual</option>
                        <option value="sick">Sick</option>
                        <option value="emergency">Emergency</option>
                        <option value="unpaid">Unpaid</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" selected>Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reason</label>
                    <textarea name="reason" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary text-white">
                    <i class="fas fa-save"></i> Save
                </button>
            </form>
        </div>
    </div>
@endsection
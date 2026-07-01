@extends('layouts.dashboard')

@section('title', 'Leave Requests')
@section('page-title', 'Leave Requests')

@section('page-actions')
    <a href="{{ route('leave-requests.create') }}" class="btn btn-primary text-white">
        <i class="fas fa-plus"></i> Add Leave Request
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-calendar-alt me-2"></i> Leave Requests List
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Reason</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($leaveRequests as $leave)
                            <tr>
                                <td class="fw-bold">{{ $leave->user->name ?? '-' }}</td>
                                <td>{{ ucfirst($leave->leave_type) }}</td>
                                <td>{{ $leave->start_date }}</td>
                                <td>{{ $leave->end_date }}</td>
                                <td>
                                    @if ($leave->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif ($leave->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $leave->reason ?? '-' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('leave-requests.destroy', $leave->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this leave request?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger text-white">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No leave requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $leaveRequests->links() }}
            </div>
        </div>
    </div>
@endsection
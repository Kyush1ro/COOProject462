@extends('layouts.dashboard')

@section('title', 'Work Orders')
@section('page-title', 'Work Orders')

@section('page-actions')
    <a href="{{ route('work-orders.create') }}" class="btn btn-primary text-white">
        <i class="fas fa-plus"></i> Add Work Order
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-clipboard-list me-2"></i> Work Orders List
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
                            <th>WO Number</th>
                            <th>Title</th>
                            <th>Equipment</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Planned Start</th>
                            <th>Planned End</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($workOrders as $wo)
                            <tr>
                                <td class="fw-bold">{{ $wo->work_order_number }}</td>
                                <td>{{ $wo->title }}</td>
                                <td>{{ $wo->equipment->name ?? '-' }}</td>
                                <td>{{ $wo->assignedUser->name ?? '-' }}</td>
                                <td>
                                    @if ($wo->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif ($wo->status === 'in_progress')
                                        <span class="badge bg-info">In Progress</span>
                                    @elseif ($wo->status === 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Open</span>
                                    @endif
                                </td>
                                <td>{{ $wo->planned_start_date ?? '-' }}</td>
                                <td>{{ $wo->planned_end_date ?? '-' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('work-orders.destroy', $wo->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this work order?');">
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
                                <td colspan="8" class="text-center text-muted py-4">
                                    No work orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $workOrders->links() }}
            </div>
        </div>
    </div>
@endsection
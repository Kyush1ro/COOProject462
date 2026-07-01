@extends('layouts.dashboard')

@section('title', 'Maintenance Requests')
@section('page-title', 'Maintenance Requests')

@section('page-actions')
    <a href="{{ route('maintenance-requests.create') }}" class="btn btn-primary text-white">
        <i class="fas fa-plus"></i> Add Request
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-tools me-2"></i> Maintenance Requests List
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
                            <th>Title</th>
                            <th>Equipment</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Requested Date</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($maintenanceRequests as $request)
                            <tr>
                                <td class="fw-bold">{{ $request->title }}</td>
                                <td>{{ $request->equipment->name ?? '-' }}</td>
                                <td>
                                    @if ($request->priority === 'critical')
                                        <span class="badge bg-danger">Critical</span>
                                    @elseif ($request->priority === 'high')
                                        <span class="badge bg-warning text-dark">High</span>
                                    @else
                                        <span class="badge bg-info">{{ ucfirst($request->priority) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</span>
                                </td>
                                <td>{{ $request->requested_date ?? '-' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('maintenance-requests.destroy', $request->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this maintenance request?');">
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
                                <td colspan="6" class="text-center text-muted py-4">
                                    No maintenance requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $maintenanceRequests->links() }}
            </div>
        </div>
    </div>
@endsection
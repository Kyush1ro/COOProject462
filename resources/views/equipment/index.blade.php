@extends('layouts.dashboard')

@section('title', 'Equipment')
@section('page-title', 'Equipment Management')

@section('page-actions')
    <a href="{{ route('equipment.create') }}" class="btn btn-primary text-white">
        <i class="fas fa-plus"></i> Add Equipment
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-cogs me-2"></i> Equipment List
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
                            <th>Code</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 160px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($equipment as $item)
                            <tr>
                                <td class="fw-bold">{{ $item->equipment_code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->type ?? '-' }}</td>
                                <td>{{ $item->location ?? '-' }}</td>
                                <td>
                                    @if ($item->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif ($item->status === 'under_maintenance')
                                        <span class="badge bg-warning text-dark">Under Maintenance</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('equipment.destroy', $item->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this equipment?');">
                                        @csrf
                                        @method('DELETE')

                                        <a href="{{ route('equipment.edit', $item->id) }}"
                                           class="btn btn-sm btn-primary text-white">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="submit" class="btn btn-sm btn-danger text-white">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No equipment found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $equipment->links() }}
            </div>
        </div>
    </div>
@endsection
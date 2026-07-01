@extends('layouts.dashboard')

@section('title', 'Spare Parts')
@section('page-title', 'Spare Parts Management')

@section('page-actions')
    <a href="{{ route('spare-parts.create') }}" class="btn btn-primary text-white">
        <i class="fas fa-plus"></i> Add Spare Part
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-boxes me-2"></i> Spare Parts List
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
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Minimum</th>
                            <th>Unit</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($spareParts as $part)
                            <tr>
                                <td class="fw-bold">{{ $part->part_code }}</td>
                                <td>{{ $part->name }}</td>
                                <td>{{ $part->category ?? '-' }}</td>
                                <td>{{ $part->quantity }}</td>
                                <td>{{ $part->minimum_quantity }}</td>
                                <td>{{ $part->unit }}</td>
                                <td>{{ $part->storage_location ?? '-' }}</td>
                                <td>
                                    @if ($part->quantity <= $part->minimum_quantity)
                                        <span class="badge bg-danger">Low Stock</span>
                                    @else
                                        <span class="badge bg-success">Available</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('spare-parts.destroy', $part->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this spare part?');">
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
                                <td colspan="9" class="text-center text-muted py-4">
                                    No spare parts found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $spareParts->links() }}
            </div>
        </div>
    </div>
@endsection
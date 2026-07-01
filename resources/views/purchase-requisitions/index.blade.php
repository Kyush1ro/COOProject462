@extends('layouts.dashboard')

@section('title', 'Purchase Requisitions')
@section('page-title', 'Purchase Requisitions')

@section('page-actions')
    <a href="{{ route('purchase-requisitions.create') }}" class="btn btn-primary text-white">
        <i class="fas fa-plus"></i> Add PR
    </a>
@endsection

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <i class="fas fa-file-invoice me-2"></i> Purchase Requisitions List
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
                            <th>PR Number</th>
                            <th>Item</th>
                            <th>Spare Part</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Status</th>
                            <th>Required Date</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($purchaseRequisitions as $pr)
                            <tr>
                                <td class="fw-bold">{{ $pr->pr_number }}</td>
                                <td>{{ $pr->item_name }}</td>
                                <td>{{ $pr->sparePart->name ?? '-' }}</td>
                                <td>{{ $pr->quantity }}</td>
                                <td>{{ $pr->unit }}</td>
                                <td>
                                    @if ($pr->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif ($pr->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif ($pr->status === 'ordered')
                                        <span class="badge bg-info">Ordered</span>
                                    @elseif ($pr->status === 'received')
                                        <span class="badge bg-primary">Received</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $pr->required_date ?? '-' }}</td>
                                <td class="text-center">
                                    <form action="{{ route('purchase-requisitions.destroy', $pr->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this purchase requisition?');">
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
                                    No purchase requisitions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $purchaseRequisitions->links() }}
            </div>
        </div>
    </div>
@endsection
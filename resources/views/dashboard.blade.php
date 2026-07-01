@extends('layouts.dashboard')

@section('title', 'Dashboard')
@section('page-title', 'Petrochemical ERP Dashboard')

@section('content')
    <div class="row g-4 mb-4">

        <div class="col-md-4 col-xl-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <div class="fs-4 fw-bold">{{ $totalEquipment }}</div>
                    <div>Total Equipment</div>
                    <i class="fas fa-cogs fa-2x mt-3"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <div class="fs-4 fw-bold">{{ $openMaintenanceRequests }}</div>
                    <div>Open Maintenance Requests</div>
                    <i class="fas fa-tools fa-2x mt-3"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body">
                    <div class="fs-4 fw-bold">{{ $activeWorkOrders }}</div>
                    <div>Active Work Orders</div>
                    <i class="fas fa-clipboard-list fa-2x mt-3"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <div class="fs-4 fw-bold">{{ $lowStockParts }}</div>
                    <div>Low Stock Spare Parts</div>
                    <i class="fas fa-boxes fa-2x mt-3"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <div class="fs-4 fw-bold">{{ $pendingPRs }}</div>
                    <div>Pending Purchase Requisitions</div>
                    <i class="fas fa-file-invoice fa-2x mt-3"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card text-white bg-secondary shadow-sm">
                <div class="card-body">
                    <div class="fs-4 fw-bold">{{ $pendingLeaveRequests }}</div>
                    <div>Pending Leave Requests</div>
                    <i class="fas fa-calendar-alt fa-2x mt-3"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-xl-3">
            <div class="card text-white bg-dark shadow-sm">
                <div class="card-body">
                    <div class="fs-4 fw-bold">{{ $totalUsers }}</div>
                    <div>Total Users</div>
                    <i class="fas fa-users fa-2x mt-3"></i>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4">

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-tools me-2"></i> Recent Maintenance Requests
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Equipment</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentMaintenanceRequests as $request)
                                    <tr>
                                        <td class="fw-bold">{{ $request->title }}</td>
                                        <td>{{ $request->equipment->name ?? '-' }}</td>
                                        <td>{{ ucfirst($request->priority) }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No maintenance requests yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('maintenance-requests.index') }}" class="btn btn-sm btn-primary text-white">
                            View All
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <i class="fas fa-clipboard-list me-2"></i> Recent Work Orders
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>WO Number</th>
                                    <th>Title</th>
                                    <th>Equipment</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentWorkOrders as $wo)
                                    <tr>
                                        <td class="fw-bold">{{ $wo->work_order_number }}</td>
                                        <td>{{ $wo->title }}</td>
                                        <td>{{ $wo->equipment->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ ucfirst(str_replace('_', ' ', $wo->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No work orders yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('work-orders.index') }}" class="btn btn-sm btn-primary text-white">
                            View All
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
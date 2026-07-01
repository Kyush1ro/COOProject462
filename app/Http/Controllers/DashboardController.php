<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\MaintenanceRequest;
use App\Models\PurchaseRequisition;
use App\Models\SparePart;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\LeaveRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEquipment = Equipment::count();

        $openMaintenanceRequests = MaintenanceRequest::whereIn('status', [
            'pending',
            'approved',
            'in_progress',
        ])->count();

        $activeWorkOrders = WorkOrder::whereIn('status', [
            'open',
            'in_progress',
        ])->count();

        $lowStockParts = SparePart::whereColumn('quantity', '<=', 'minimum_quantity')->count();

        $pendingPRs = PurchaseRequisition::where('status', 'pending')->count();

        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')->count();

        $totalUsers = User::count();

        $recentMaintenanceRequests = MaintenanceRequest::with('equipment')
            ->latest()
            ->take(5)
            ->get();

        $recentWorkOrders = WorkOrder::with('equipment')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalEquipment',
            'openMaintenanceRequests',
            'activeWorkOrders',
            'lowStockParts',
            'pendingPRs',
            'pendingLeaveRequests',
            'totalUsers',
            'recentMaintenanceRequests',
            'recentWorkOrders'
        ));
    }
}
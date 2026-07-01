<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\MaintenanceRequest;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Http\Request;

class WorkOrderController extends Controller
{
    public function index()
    {
        $workOrders = WorkOrder::with(['equipment', 'maintenanceRequest', 'assignedUser'])
            ->latest()
            ->paginate(10);

        return view('work-orders.index', compact('workOrders'));
    }

    public function create()
    {
        $equipment = Equipment::orderBy('name')->get();
        $maintenanceRequests = MaintenanceRequest::orderBy('title')->get();
        $users = User::orderBy('name')->get();

        return view('work-orders.create', compact('equipment', 'maintenanceRequests', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'maintenance_request_id' => 'nullable|exists:maintenance_requests,id',
            'equipment_id' => 'required|exists:equipment,id',
            'assigned_to' => 'nullable|exists:users,id',
            'work_order_number' => 'required|string|max:255|unique:work_orders,work_order_number',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|max:255',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date',
        ]);

        WorkOrder::create($data);

        return redirect()->route('work-orders.index')->with('success', 'Work order created successfully.');
    }

    public function show(WorkOrder $workOrder)
    {
        $workOrder->load(['equipment', 'maintenanceRequest', 'assignedUser']);
        return view('work-orders.show', compact('workOrder'));
    }

    public function edit(WorkOrder $workOrder)
    {
        $equipment = Equipment::orderBy('name')->get();
        $maintenanceRequests = MaintenanceRequest::orderBy('title')->get();
        $users = User::orderBy('name')->get();

        return view('work-orders.edit', compact('workOrder', 'equipment', 'maintenanceRequests', 'users'));
    }

    public function update(Request $request, WorkOrder $workOrder)
    {
        $data = $request->validate([
            'maintenance_request_id' => 'nullable|exists:maintenance_requests,id',
            'equipment_id' => 'required|exists:equipment,id',
            'assigned_to' => 'nullable|exists:users,Academic_ID',
            'work_order_number' => 'required|string|max:255|unique:work_orders,work_order_number,' . $workOrder->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string|max:255',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => 'nullable|date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date',
        ]);

        $workOrder->update($data);

        return redirect()->route('work-orders.index')->with('success', 'Work order updated successfully.');
    }

    public function destroy(WorkOrder $workOrder)
    {
        $workOrder->delete();

        return redirect()->route('work-orders.index')->with('success', 'Work order deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        $maintenanceRequests = MaintenanceRequest::with(['equipment', 'requester'])
            ->latest()
            ->paginate(10);

        return view('maintenance-requests.index', compact('maintenanceRequests'));
    }

    public function create()
    {
        $equipment = Equipment::orderBy('name')->get();
        return view('maintenance-requests.create', compact('equipment'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'requested_date' => 'nullable|date',
        ]);

$data['requested_by'] = auth()->id();
        MaintenanceRequest::create($data);

        return redirect()->route('maintenance-requests.index')->with('success', 'Maintenance request created successfully.');
    }

    public function show(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->load(['equipment', 'requester']);
        return view('maintenance-requests.show', compact('maintenanceRequest'));
    }

    public function edit(MaintenanceRequest $maintenanceRequest)
    {
        $equipment = Equipment::orderBy('name')->get();
        return view('maintenance-requests.edit', compact('maintenanceRequest', 'equipment'));
    }

    public function update(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $data = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'requested_date' => 'nullable|date',
        ]);

        $maintenanceRequest->update($data);

        return redirect()->route('maintenance-requests.index')->with('success', 'Maintenance request updated successfully.');
    }

    public function destroy(MaintenanceRequest $maintenanceRequest)
    {
        $maintenanceRequest->delete();

        return redirect()->route('maintenance-requests.index')->with('success', 'Maintenance request deleted successfully.');
    }
}
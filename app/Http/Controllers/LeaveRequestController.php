<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $leaveRequests = LeaveRequest::with(['user', 'approver'])
            ->latest()
            ->paginate(10);

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('leave-requests.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string|max:255',
            'reason' => 'nullable|string',
        ]);

        $data['approved_by'] = null;

        LeaveRequest::create($data);

        return redirect()->route('leave-requests.index')->with('success', 'Leave request created successfully.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['user', 'approver']);
        return view('leave-requests.show', compact('leaveRequest'));
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        $users = User::orderBy('name')->get();
        return view('leave-requests.edit', compact('leaveRequest', 'users'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,Academic_ID',
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|string|max:255',
            'reason' => 'nullable|string',
        ]);

        if ($data['status'] === 'approved') {
            $data['approved_by'] = auth()->user()->Academic_ID ?? null;
        }

        $leaveRequest->update($data);

        return redirect()->route('leave-requests.index')->with('success', 'Leave request updated successfully.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();

        return redirect()->route('leave-requests.index')->with('success', 'Leave request deleted successfully.');
    }
}
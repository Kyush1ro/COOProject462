<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequisition;
use App\Models\SparePart;
use Illuminate\Http\Request;

class PurchaseRequisitionController extends Controller
{
    public function index()
    {
        $purchaseRequisitions = PurchaseRequisition::with(['sparePart', 'requester'])
            ->latest()
            ->paginate(10);

        return view('purchase-requisitions.index', compact('purchaseRequisitions'));
    }

    public function create()
    {
        $spareParts = SparePart::orderBy('name')->get();
        return view('purchase-requisitions.create', compact('spareParts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'spare_part_id' => 'nullable|exists:spare_parts,id',
            'pr_number' => 'required|string|max:255|unique:purchase_requisitions,pr_number',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'justification' => 'nullable|string',
            'required_date' => 'nullable|date',
        ]);

$data['requested_by'] = auth()->id();
        PurchaseRequisition::create($data);

        return redirect()->route('purchase-requisitions.index')->with('success', 'Purchase requisition created successfully.');
    }

    public function show(PurchaseRequisition $purchaseRequisition)
    {
        $purchaseRequisition->load(['sparePart', 'requester']);
        return view('purchase-requisitions.show', compact('purchaseRequisition'));
    }

    public function edit(PurchaseRequisition $purchaseRequisition)
    {
        $spareParts = SparePart::orderBy('name')->get();
        return view('purchase-requisitions.edit', compact('purchaseRequisition', 'spareParts'));
    }

    public function update(Request $request, PurchaseRequisition $purchaseRequisition)
    {
        $data = $request->validate([
            'spare_part_id' => 'nullable|exists:spare_parts,id',
            'pr_number' => 'required|string|max:255|unique:purchase_requisitions,pr_number,' . $purchaseRequisition->id,
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'justification' => 'nullable|string',
            'required_date' => 'nullable|date',
        ]);

        $purchaseRequisition->update($data);

        return redirect()->route('purchase-requisitions.index')->with('success', 'Purchase requisition updated successfully.');
    }

    public function destroy(PurchaseRequisition $purchaseRequisition)
    {
        $purchaseRequisition->delete();

        return redirect()->route('purchase-requisitions.index')->with('success', 'Purchase requisition deleted successfully.');
    }
}
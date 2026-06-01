<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Semester;
use Illuminate\Support\Facades\Auth;

class SemesterController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $semesters = Semester::orderBy('id', 'desc')->get();
        return view('semesters.index', compact('semesters'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('semesters.create');
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'id' => 'required|integer|unique:semesters,id', // Manual ID
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        // If setting this to active, deactivate others
        if ($request->has('is_active') && $request->is_active) {
            Semester::where('is_active', true)->update(['is_active' => false]);
            $validated['is_active'] = true;
        } else {
            $validated['is_active'] = false;
        }

        Semester::create($validated);

        return redirect()->route('semesters.index')->with('success', 'Semester created successfully.');
    }

    public function edit(Semester $semester)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        return view('semesters.edit', compact('semester'));
    }

    public function update(Request $request, Semester $semester)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        // If setting this to active, deactivate others
        if ($request->has('is_active') && $request->is_active) {
            Semester::where('id', '!=', $semester->id)->update(['is_active' => false]);
            $validated['is_active'] = true;
        } else {
            // Prevent deactivating the only active semester without activating another?
            // For now, allow it, but maybe warn.
            $validated['is_active'] = $request->has('is_active');
        }

        $semester->update($validated);

        return redirect()->route('semesters.index')->with('success', 'Semester updated successfully.');
    }

    public function destroy(Semester $semester)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        // Optional: Check for courses
        if ($semester->courses()->exists()) {
             return back()->with('error', 'Cannot delete semester with associated courses.');
        }

        $semester->delete();

        return redirect()->route('semesters.index')->with('success', 'Semester deleted successfully.');
    }
}

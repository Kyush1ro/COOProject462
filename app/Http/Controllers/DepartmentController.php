<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        $departments = Department::latest()->get();

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        return view('departments.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:departments,code'],
        ]);

        Department::create($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('departments', 'code')->ignore($department->id),
            ],
        ]);

        $department->update($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $user = Auth::user();

        if (! $user->isAdmin()) {
            abort(403);
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
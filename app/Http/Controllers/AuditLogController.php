<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the resource (Audit Logs).
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Strictly Admin Only
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        // 2. Fetch all logs, newest first, eager load the user who made the change
        $logs = AuditLog::with('user')->latest()->paginate(25);

        // 3. Return View
        return view('logs.index', compact('logs'));
    }

    // Since logs are read-only, we don't need create, store, edit, or update methods.

    /**
     * Remove the specified resource from storage (Optional: Clear Logs).
     */
    public function destroy(string $id)
    {


        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Strictly Admin Only
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }


        // This method allows clearing the logs table entirely.
        // You would typically add a confirmation check here.
        AuditLog::truncate();

        return redirect()->route('logs.index')->with('success', 'Audit logs cleared successfully.');
    }
}

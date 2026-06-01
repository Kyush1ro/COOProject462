<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $role = $user->role ?? 'employee';

        $dashboardTitle = match ($role) {
            'admin' => 'Admin Dashboard',
            'planner' => 'Planner Dashboard',
            'hr' => 'HR Dashboard',
            'warehouse' => 'Warehouse Dashboard',
            default => 'Employee Dashboard',
        };

        return view('dashboard', compact('user', 'role', 'dashboardTitle'));
    }
}
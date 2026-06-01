<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('attendance.index');
    }

    public function store(Request $request)
    {
        return back()->with('info', 'Attendance module will be updated for HR employee attendance.');
    }
}
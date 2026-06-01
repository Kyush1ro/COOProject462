<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'name');
        $semesters = \App\Models\Semester::orderBy('id', 'desc')->get();
        return view('admin.settings.index', compact('settings', 'semesters'));
    }

    public function store(Request $request)
    {
        $keys = ['registration_open', 'enrollment_open', 'current_semester', 'drop_deadline'];

        foreach ($keys as $key) {
            $value = $request->input($key);
            
            // Handle boolean toggles
            if ($key === 'registration_open' || $key === 'enrollment_open') {
                $value = $request->has($key) ? '1' : '0';
            }

            \App\Models\Setting::setValue($key, $value);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}

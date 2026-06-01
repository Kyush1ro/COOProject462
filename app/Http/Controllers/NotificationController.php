<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display user notifications.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Fetch database notifications (requires 'notifications' table)
        // If the table isn't set up, this might error, so ensure you run:
        // php artisan notifications:table
        // php artisan migrate
        
        $notifications = $user->notifications()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark all as read.
     */
    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }
}
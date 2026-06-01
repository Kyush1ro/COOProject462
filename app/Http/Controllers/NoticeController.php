<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\SystemNotice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Traits\NotifiesN8n;


class NoticeController extends Controller
{
    use NotifiesN8n;
    // Display notifications for the current user
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    // Show form to create a new notice (Admin only)
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Fetch users to populate the selection list, excluding the current admin
        $users = User::where('Academic_ID', '!=', Auth::user()->Academic_ID)->get();

        return view('notifications.create', compact('users'));
    }

    // Send the notice (Admin only)
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|array', // Array of User IDs
            'recipients.*' => 'exists:users,Academic_ID',
        ]);

        // Filter out the current user just in case they were somehow selected
        $recipients = User::whereIn('Academic_ID', $validated['recipients'])
            ->where('Academic_ID', '!=', Auth::user()->Academic_ID)
            ->get();

        // Send notification
        Notification::send($recipients, new SystemNotice(
            $validated['subject'],
            $validated['message'],
            Auth::user()->name
        ));

        $recipientEmails = $recipients->pluck('email')->toArray();
        $this->sendToN8n('notice', [
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'sender_name' => Auth::user()->name,
        ], $recipientEmails);

        return redirect()->route('notifications.create')->with('success', 'Notice sent successfully to ' . $recipients->count() . ' users.');
    }

    // Mark a specific notification as read
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return back();
    }

    // Mark all as read
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }
}

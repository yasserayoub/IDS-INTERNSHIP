<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest('CreatedAt')
            ->paginate(15);

        return view(
            'notifications.index',
            compact('notifications')
        );
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('Id', $id)
            ->where('UserId', Auth::id())
            ->firstOrFail();

        $notification->IsRead = true;
        $notification->ReadAt = now();
        $notification->UpdatedAt = now();

        $notification->save();

        return redirect()->back();
    }
    public function markAllAsRead()
{
    Auth::user()
        ->notifications()
        ->where('IsRead', false)
        ->update([
            'IsRead' => true,
            'ReadAt' => now(),
            'UpdatedAt' => now(),
        ]);

    return redirect()->back()
        ->with('success', 'All notifications marked as read.');
}
}

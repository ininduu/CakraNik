<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $notifications = $user->notifications()->paginate(15);

        return view("notifications.index", compact("notifications"));
    }

    public function markAsRead(string $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $notification = $user->unreadNotifications()->where("id", $id)->firstOrFail();
        $notification->markAsRead();

        return back()->with("success", "Notifikasi ditandai telah dibaca.");
    }

    public function markAllAsRead()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $user->unreadNotifications->markAsRead();

        return back()->with("success", "Semua notifikasi ditandai telah dibaca.");
    }
}
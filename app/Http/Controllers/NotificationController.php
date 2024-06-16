<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function destroy($id)
    {
        $notification = DatabaseNotification::find($id);

        if ($notification && $notification->notifiable_id == Auth::id()) {
            $notification->delete();
        }

        $username = Auth::user()->username;
        $menu = 'notifications';

        return redirect()->route('profile', ['username' => $username, 'menu' => $menu])->with('success', 'Notification deleted successfully.');
    }
}

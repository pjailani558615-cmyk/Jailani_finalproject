<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller {

    public function send(Request $request) {
        $request->validate([
            'recipient_id' => 'required|string|exists:users,id',
            'sender_id' => 'required|string|exists:users,id',
            'message'        => 'required|in:pending,approved,rejected',
        ]);

        $recipient = User::where('id', $request->recipient_id)->firstOrFail();
        $sender = User::where('id', $request->sender_id)->firstOrFail();

        Notification::create([
            'recipient_id' => $recipient->id,
            'sender_id'    => $sender->id,
            'message'      => $request->message,
        ]);

        return redirect()->back()->with('notification-sent', true);
    }
}
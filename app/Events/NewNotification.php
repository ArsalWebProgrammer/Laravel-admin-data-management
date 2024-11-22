<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Notification;
use App\Events\NewNotification;

class NewNotification implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new Channel('notifications');
    }
}
class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $notification = Notification::create([
            'message' => $request->message,
            'user_id' => $request->user()->id,
            'created_at' => now(),
        ]);

        broadcast(new NewNotification($notification));

        return response()->json(['success' => 'Notification created!']);
    }
}
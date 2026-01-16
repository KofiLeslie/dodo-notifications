<?php

namespace App\Notifications;

use App\Models\Notification as NotificationModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class UserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected NotificationModel $notification
    ) {}

    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id'           => $this->notification->id,
            'message'      => $this->notification->message,
            'type'         => $this->notification->type,
            'from_user_id' => $this->notification->from_user_id,
            'created_at'   => $this->notification->created_at->toISOString(),
        ]);
    }

    /**
     * Broadcast on a private user channel
     */
    public function broadcastOn(): array
    {
        return [
            new \Illuminate\Broadcasting\PrivateChannel(
                'App.Models.User.' . $this->notification->user_id
            )
        ];
    }
}

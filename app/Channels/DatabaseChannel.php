<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Log;

class DatabaseChannel extends IlluminateDatabaseChannel
{
    /**
    * Send the given notification.
    *
    * @param mixed $notifiable
    * @param \Illuminate\Notifications\Notification $notification
    * @return \Illuminate\Database\Eloquent\Model
    */
    public function send($notifiable, Notification $notification)
    {
        $data = $this->getData($notifiable, $notification);

        Log::info($notifiable);
        Log::info($data);

        return $notifiable->routeNotificationFor('database')->create([
            'id' => $notification->id,
            'type' => get_class($notification),
            'data' => $data,
            'read_at' => null,
            'model' => $data['click_action_page'] ?? '',
            'model_id' => $data['model_id'] ?? ''
        ]);
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Message implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $id;
    public $message;
    public $userId;
    public $username;
    public $discussionId;
    public $createdAt;
    public $avatar;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $message, $userId, $username, $discussionId, $createdAt, $avatar)
    {
        $this->id = $id;
        $this->message = $message;
        $this->userId = $userId;
        $this->username = $username; 
        $this->discussionId = $discussionId;
        $this->createdAt = $createdAt;
        $this->avatar = $avatar;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('chat'); //['chat_'.$this->discussionId]; //new PrivateChannel('channel-name');
    }

    public function broadcastAs()
    {
        return 'message'; //'message_'.$this->discussionId;
    }
}

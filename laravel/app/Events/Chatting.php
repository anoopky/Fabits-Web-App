<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Chatting implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $userID;

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @param mixed $userID
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userID)
    {
        $this->setUserID($userID);
    }
    public function broadcastAs()
    {
        return 'chat';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('fabits'.$this->getUserID()."chatting");
    }
}

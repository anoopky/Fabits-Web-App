<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Typing implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $userID;
    public $conversationID;

    /**
     * @return mixed
     */
    public function getConversationID()
    {
        return $this->conversationID;
    }

    /**
     * @param mixed $conversationID
     */
    public function setConversationID($conversationID)
    {
        $this->conversationID = $conversationID;
    }

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
    public function __construct($userID, $conversationID)
    {
        $this->setUserID($userID);
        $this->setConversationID($conversationID);
    }
    public function broadcastAs()
    {
        return 'typing';
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
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SeenReadConversation implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $conversationId;
    public $message;

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


    /**
     * @return mixed
     */
    public function getConversationId()
    {
        return $this->conversationId;
    }

    /**
     * @param mixed $conversationId
     */
    public function setConversationId($conversationId)
    {
        $this->conversationId = $conversationId;
    }


    public function __construct($conversationId, $message)
    {

        $this->setConversationId($conversationId);
        $this->setMessage($message);
    }


    public function broadcastWith()
    {
        return array($this->getMessage());
    }


    public function broadcastAs()
    {
        return 'SeenDeliverChange';
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('fabits'.$this->getConversationId());
    }
}
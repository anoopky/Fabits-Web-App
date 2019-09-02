<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class Universal implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
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
     * Create a new event instance.
     *
     * @param $message
     */

    public function __construct($message)
    {
        $this->setMessage($message);
    }


    public function broadcastAs()
    {
        return 'universal';
    }

    public function broadcastOn()
    {
        return new Channel('fabits');
    }
}

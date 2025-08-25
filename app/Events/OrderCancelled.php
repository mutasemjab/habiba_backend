<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCancelled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $orderDetails;
    /**
     * Create a new event instance.
     */
    public function __construct(array $orderDetails)
    {
        $this->orderDetails = $orderDetails;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return ['orders'];
    }

    /**
     * Customize the data to be broadcasted.
     */
    public function broadcastWith()
    {
        return $this->orderDetails;
    }

    /**
     * Customize the event name.
     */
    public function broadcastAs()
    {
        return 'order.cancelled';
    }
}
<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Specify the channel name for broadcasting.
     */
    public function broadcastOn()
    {
        return ['orders'];
    }

    /**
     * Define the event name for broadcasting.
     */
    public function broadcastAs()
    {
        return 'order.created';
    }

    /**
     * Customize the data to be broadcasted.
     */
    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'client_name' => $this->order->client->name ?? 'Guest',
            'status' => $this->order->status,
            'total_price' => number_format($this->order->total_price, 2),
            'created_at' => $this->order->created_at->format('d-m-Y H:i:s'),
        ];
    }
}
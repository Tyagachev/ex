<?php

namespace App\Events\Share;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ShareCount
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public object $obj;

    /**
     * Create a new event instance.
     */
    public function __construct(object $obj)
    {
        $this->obj = $obj;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}

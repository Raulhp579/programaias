<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnalisisTerminado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $respuesta;
    /**
     * Create a new event instance.
     */
    public function __construct($respuesta)
    {
        $this->respuesta = $respuesta;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('analisisIA'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'analisis';
    }

    public function broadcastWith(): array
    {
        return [
            'respuesta' => $this->respuesta,
        ];
    }
}

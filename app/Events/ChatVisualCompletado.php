<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatVisualCompletado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $respuesta;
    public $success;
    public $mensaje;

    /**
     * Create a new event instance.
     */
    public function __construct($success, $respuesta, $mensaje = null)
    {
        $this->success = $success;
        $this->respuesta = $respuesta;
        $this->mensaje = $mensaje;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('chatVisualChannel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'completado';
    }

    public function broadcastWith(): array
    {
        return [
            'success' => $this->success,
            'respuesta' => $this->respuesta,
            'mensaje' => $this->mensaje,
        ];
    }
}

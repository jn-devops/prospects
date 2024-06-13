<?php

namespace Homeful\Prospects\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Homeful\Prospects\Model\Prospect;
use Illuminate\Broadcasting\Channel;

class ProspectAuthenticated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Prospect $prospect;

    public function __construct(Prospect $prospect)
    {
        $this->prospect = $prospect;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('reference.' . $this->prospect->reference_code),
        ];
    }

    public function broadcastAs(): string
    {
        return 'prospect.authenticated';
    }

    public function broadcastWith(): array
    {
        return [
            'prospect' => $this->prospect->toArray(),
        ];
    }
}

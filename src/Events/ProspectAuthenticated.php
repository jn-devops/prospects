<?php

namespace Homeful\Prospects\Events;

use Homeful\Prospects\Model\Prospect;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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
            new Channel('reference.'.$this->prospect->reference_code),
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

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaperRecordItemSaved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $recordItem;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $recordItem)
    {
        $this->recordItem = $recordItem;
    }

    public function getRecordItem()
    {
        return $this->recordItem;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

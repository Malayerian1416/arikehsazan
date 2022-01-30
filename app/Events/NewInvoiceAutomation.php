<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewInvoiceAutomation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $new_invoice_automation_count;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($new_invoice_automation_count)
    {
        $this->new_invoice_automation_count = $new_invoice_automation_count;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('new_invoice_automation');
    }
}

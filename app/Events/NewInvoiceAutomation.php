<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewInvoiceAutomation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $automation;

    public function __construct($automation)
    {
        $this->automation = $automation;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('invoice_automation.'.$this->automation->current_role_id);
    }
}

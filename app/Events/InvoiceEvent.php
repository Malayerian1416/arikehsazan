<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use phpDocumentor\Reflection\Types\Collection;

class InvoiceEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $automation,$message;

    public function __construct($automation,$message)
    {
        $this->automation = $automation;
        $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('invoice_automation.'.$this->automation->current_role_id);
    }
    public function broadcastWith(): array
    {
        return ['action_route' => route("InvoiceAutomation.automation"),"message" => $this->message];
    }
}

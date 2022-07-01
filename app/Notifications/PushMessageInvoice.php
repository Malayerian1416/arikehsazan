<?php

namespace App\Notifications;

use App\Models\CompanyInformation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PushMessageInvoice extends Notification
{
    use Queueable;
    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }
    public $message;
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('اریکه سازان')
            ->icon('/img/new_notification.png')
            ->body($this->message)
            ->options(['TTL' => 1000])
            ->data(['action_route' => route("InvoiceAutomation.automation")])
            ->badge("/img/notification_badge.png");
    }
}

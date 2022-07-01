<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PushMessageWorker extends Notification
{
    use Queueable;

    public $message;

    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }
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
            ->data(['action_route' => route("LeaveAutomation.automation")])
            ->badge("/img/notification_badge.png");
    }
}

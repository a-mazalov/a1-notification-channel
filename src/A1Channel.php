<?php

namespace A1\Channel;

use A1\Channel\Exceptions\ExceptionA1Notification;
use Illuminate\Notifications\Notification;

class A1Channel
{
    protected $client;

    public function __construct(A1Client $client)
    {
        $this->client = $client;
    }

    public function send($notifiable, Notification $notification)
    {
        if (method_exists($notifiable, 'routeNotificationForA1')) {
            $phoneNumber = $notifiable->routeNotificationForA1($notifiable);
        } else {
            $phoneNumber = $notifiable->mtel;
        }

        $message = $notification->toA1($notifiable);

        if ($message && empty($message->content)) {
            throw ExceptionA1Notification::attemptingToSendEmptyMessage();
            return;
        }

        return $this->client->sendSms($phoneNumber, $message->content);
    }
}

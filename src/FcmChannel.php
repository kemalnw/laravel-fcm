<?php

namespace Kemalnw\Fcm;

use Illuminate\Notifications\Notification;
use Kemalnw\Fcm\Exception\InvalidRecipientsException;

class FcmChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message    = $notification->toFcm($notifiable);
        $recipients = $notifiable->firebase_uid ?? $notifiable->routeNotificationFor('fcm', $notification);
        if (! $recipients) {
            throw new InvalidRecipientsException("You must provide a `firebase token` property on your notifiable entity.");
        }

        return $message
            ->to($recipients)
            ->send();
    }
}

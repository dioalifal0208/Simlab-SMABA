<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifyNotificationEmail extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = URL::temporarySignedRoute(
            'notification-email.verify',
            now()->addMinutes(60),
            ['id' => $notifiable->id]
        );

        return (new MailMessage)
            ->mailer('smtp-auth')
            ->subject('Verifikasi Email Notifikasi — Lab SMABA')
            ->view('emails.verify-notification-email', [
                'user' => $notifiable,
                'verificationUrl' => $verificationUrl,
            ]);
    }

    /**
     * Route notification ke notification_email (bukan email login).
     */
    public function routeNotificationFor($driver, $notifiable = null)
    {
        // Override: kirim ke notification_email, bukan email login
        $target = $notifiable ?? $this;
        return $target->notification_email;
    }
}

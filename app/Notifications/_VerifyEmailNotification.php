<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $frontendUrl = config('app.frontend_url', 'http://localhost:5173');

        // Generamos el enlace firmado con la ruta correcta de Laravel
        $temporarySignedUrl = URL::temporarySignedRoute(
            'verification.verify', // Nombre de la ruta en auth.php
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        // Convertimos la URL para que apunte a Vue en lugar de Laravel
        $verificationUrl = str_replace(
            url('/verify-email'),
            $frontendUrl . '/auth/verify-email',
            $temporarySignedUrl
        );

        return (new MailMessage)
            ->subject('Verifica tu dirección de correo electrónico')
            ->line('Haz clic en el botón para verificar tu email.')
            ->action('Verificar Email', $verificationUrl)
            ->line('Si no solicitaste este email, ignóralo.');
    }
}

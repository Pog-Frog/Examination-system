<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class UserVerifyEmail extends Notification
{
    use Queueable;

    public static $toMailCallback;
    
    public static $createUrlCallback;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }
    
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return $this->buildMailMessage($this->resetUrl($notifiable));
    }

    protected function buildMailMessage($url)
    {
        return (new MailMessage)
            ->subject(Lang::get('Email verfication Notification'))
            ->line(Lang::get('You are receiving this email because this email was used to register an account on our platform.'))
            ->action(Lang::get('Verify Email'), $url)
            ->line(Lang::get('If you did not make this request, no further action is required.'));
    }

    protected function resetUrl($notifiable)
    {
        if (static::$createUrlCallback) {
            return call_user_func(static::$createUrlCallback, $notifiable, $this->token);
        }
        return url(route('student_verify_email.get', [
            'token' => $this->token,
            'email' => preg_replace('/@/', '%40', $this->email),
        ], false));
    }

    public static function createUrlUsing($callback)
    {
        static::$createUrlCallback = $callback;
    }
    
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}

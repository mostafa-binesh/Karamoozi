<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordReset extends Notification
{
    use Queueable;
    public $token;
    public $email;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token,$email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('بازیابی رمز عبور')
                    ->line('شما درخواست تغییر رمز خود را داده بودید. با کلیک بر روی لینک زیر، می توانید وارد سایت شوید و رمز جدیدی برای حسابتان انتخاب کنید.')
                    ->action('بازیابی رمز عبور', route('password.reset',['token' => $this->token, 'email' => $this->email]))
                    // ->action('بازیابی رمز عبور', url('/') . $this->token)
                    ->line('از اینکه از سایت ما استفاده می کنید سپاسگزاریم.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

<?php

namespace PavelVasilyev\AuthAjax\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewVerifyEmailNotification extends Notification
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        $siteName = env('APP_NAME');
        $url = env('APP_URL').'/verify/'.$this->user->id.'/'.$this->user->verify_token;
        return (new MailMessage)
            ->subject('Инструкция по активации аккаунта')
            ->line('Новая ссылка для активации аккаунта')
            ->line('Для активации Вашего аккаунта, созданного при регистрации на сайте ' . $siteName . ' нажмите на кнопку:')
            ->action('Активировать аккаунт', url($url))
            ->line('Благодарим за регистрацию на нашем сайте!');
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

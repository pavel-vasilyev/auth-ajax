<?php

namespace PavelVasilyev\AuthAjax\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user)
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
    public function toMail()
    {
        $siteName = env('APP_NAME');
        $url = env('APP_URL').'/verify/'.$this->user->id.'/'.$this->user->verify_token;
        return (new MailMessage)
            ->subject('Инструкция по активации аккаунта')
            ->line('Активация аккаунта')
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

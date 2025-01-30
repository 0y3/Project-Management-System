<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendAccountDetailsNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
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
            ->greeting("Dear {$this->user->name},")
            ->line('
                We hope this message finds you well. As an administrator, we are pleased
                to provide you with your account details. Please find the following information below:')
            ->line("Email Address: {$this->user->email}")
            ->line("Password: {$this->password}")
            ->action('Please click the link to login', url(config('app.url')))
            ->line('
                Please note that this information is sensitive and should be kept confidential.
                We highly recommend changing your password upon receiving this message to ensure
                the security of your account. To change your password, please follow the instructions
                provided on our platform.
            ')
            ->line("
                If you have any further questions or require assistance, please don't hesitate to reach
                out to our support team.
            ")
            ->line("Thank you for being a valued member of our community, and we appreciate your continued support.")
            // ->line("Best regards,")
            ->subject('Account Details');
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

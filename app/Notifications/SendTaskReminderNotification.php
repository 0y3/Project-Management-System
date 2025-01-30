<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendTaskReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $task;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $task)
    {
        $this->user = $user;
        $this->task = $task;
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
            ->line("This is to remind you of your overdue task titled")
            ->line("Task: {$this->task->name}")
            ->line("Please check your task list for details and let me know if you have any questions.")
            ->action('Please click the link to login', url(config('app.url')))
            ->line("
                If you have any further questions or require assistance, please don't hesitate to reach
                out to our support team.
            ")
            ->subject('Overdue Task Reminder');
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

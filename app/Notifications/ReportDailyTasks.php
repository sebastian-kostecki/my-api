<?php

namespace App\Notifications;

use App\Mail\ReportDailyTasks as ReportDailyTasksMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportDailyTasks extends Notification
{
    use Queueable;

    protected array $dailyTasks;

    protected array $nextTasks;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $dailyTasks, array $nextTasks)
    {
        $this->dailyTasks = $dailyTasks;
        $this->nextTasks = $nextTasks;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return (new ReportDailyTasksMail($this->dailyTasks, $this->nextTasks))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'dailyTasks' => $this->dailyTasks,
            'nextTasks' => $this->nextTasks,
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterviewScheduled extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $interview
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Interview Scheduled - JAE Tijuana')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('An interview has been scheduled for your application.')
            ->line('Position: ' . $this->interview->application->position->title)
            ->line('Date: ' . $this->interview->scheduled_at->format('F j, Y \a\t g:i A'))
            ->line('Type: ' . ucfirst($this->interview->type->value))
            ->line('Location: ' . ($this->interview->location ?? 'TBD'))
            ->line('Good luck with your interview!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'interview_id' => $this->interview->id,
            'scheduled_at' => $this->interview->scheduled_at,
            'type' => $this->interview->type->value,
        ];
    }
}

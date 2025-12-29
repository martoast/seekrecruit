<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $application,
        public $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusMessages = [
            'registered' => 'Your application has been registered.',
            'preselected' => 'Congratulations! You have been preselected.',
            'interview' => 'You have been selected for an interview.',
            'evaluation' => 'Your application is under evaluation.',
            'finalist' => 'Congratulations! You are now a finalist.',
            'hired' => 'Congratulations! You have been hired!',
            'discarded' => 'Unfortunately, we have decided to move forward with other candidates.',
        ];

        $message = $statusMessages[$this->newStatus] ?? 'Your application status has been updated.';

        return (new MailMessage)
            ->subject('Application Status Update - JAE Tijuana')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($message)
            ->line('Position: ' . $this->application->position->title)
            ->line('Thank you for your interest in JAE Tijuana!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'position_title' => $this->application->position->title,
            'new_status' => $this->newStatus,
        ];
    }
}

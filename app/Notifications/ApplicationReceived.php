<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $application
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Application Received - JAE Tijuana')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('We have received your application for the position: ' . $this->application->position->title)
            ->line('Our recruitment team will review your application and get back to you soon.')
            ->line('Thank you for your interest in joining JAE Tijuana!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'position_title' => $this->application->position->title,
        ];
    }
}

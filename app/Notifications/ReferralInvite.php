<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReferralInvite extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $referrerName,
        public $referralLink
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('You have been referred to JAE Tijuana')
            ->greeting('Hello!')
            ->line($this->referrerName . ' has referred you to join JAE Tijuana.')
            ->line('JAE Tijuana is looking for talented engineering professionals.')
            ->action('Apply Now', $this->referralLink)
            ->line('We look forward to receiving your application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'referrer_name' => $this->referrerName,
        ];
    }
}

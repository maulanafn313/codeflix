<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Mail\MembershipExpiredMail;
use Illuminate\Contracts\Mail\Mailable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MembershipExpiredNotification extends Notification
{
    use Queueable;

    private $membership;

    /**
     * Create a new notification instance.
     */
    public function __construct($membership)
    {
        $this->membership = $membership;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): Mailable
    {
        // return (new MailMessage)
        //     ->subject('Membership Expired | codeflix')
        //     ->greeting('Hello ' . $this->membership->name)
        //     ->line('Your membership has expired.')
        //     ->line('Your expired date is ' . $this->membership->end_date->format('d M Y'))
        //     ->action('Renew Membership', url('/renew'))
        //     ->line('Thank you for using our application!');

        return (new MembershipExpiredMail($this->membership))->to(($notifiable->email));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

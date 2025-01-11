<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $addr = $this->order->billingAddress;

        return (new MailMessage)
            ->subject('New Order Created')
            ->greeting('Hello ' . $notifiable->name)
            ->line('New order #' . $this->order->number . ' created by ' . $addr->name . ' from ' . $addr->country_name)
            ->action('View order', url('/dashboard'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase($notifiable) {

        $addr = $this->order->billingAddress;

        return [
            'title' => 'New order #' . $this->order->number . ' created by ' . $addr->name . ' from ' . $addr->country_name,
            'icon' => 'fas fa-envelope mr-2',
            'url' => url('/dashboard')
        ];

    }
    public function toBroadcast($notifiable) {

        $addr = $this->order->billingAddress;

        return [
            'title' => 'New order #' . $this->order->number . ' created by ' . $addr->name . ' from ' . $addr->country_name,
            'icon' => 'fas fa-envelope mr-2',
            'url' => url('/dashboard')
        ];

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}

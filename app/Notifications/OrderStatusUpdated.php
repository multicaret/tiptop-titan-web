<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public Order $order;

    /**
     * Create a new notification instance.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Todo: send the notification based on the user preference
        // $notifiable->settings
        return [
            OneSignalChannel::class,
            'database',
        ];
    }


    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
                               ->setSubject('🚚 Your order has been send!')
                               ->setBody('Click here to see details.')
                               ->setUrl('http://onesignal.com')
                               ->setWebButton(
                                   OneSignalWebButton::create('link-1')
                                                     ->text('Click here')
                                                     ->icon('https://upload.wikimedia.org/wikipedia/commons/4/4f/Laravel_logo.png')
                                                     ->url('http://laravel.com')
                               );
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
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
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
            'subject_id' => $notifiable->id,
            'object_id' => $this->order->id,
        ];
    }
}

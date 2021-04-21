<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use NotificationChannels\OneSignal\OneSignalChannel;

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
        $this->body = "🚚 Your order now is {$this->order->getStatusName()}!";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        $shouldBeSend = $this->sendToRoleIfStatus($notifiable->role_name, $this->order->status);
        if ( ! $shouldBeSend) {
            return [];
        }

        $via = [
            OneSignalChannel::class,
            'database',
//            'mail',
        ];

        return $via;
    }
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    /*public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }*/

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            /* View Related */
//            'title' => $this->title,
            'body' => $this->body,
            'subject_id' => $notifiable->id,
            'subject_title' => $notifiable->name,
            'object_id' => $this->order->id,
            'object_title' => $this->order->reference_code,
            'icon' => $this->order->is_food ? 'fa-concierge-bell' : 'fa-shopping-basket',
            'image' => null,
            /* Navigation Related */
            'route' => [
                'name' => 'admin.orders.show',
                'variables' => [$this->order->id],
                'params' => [],
            ],
            /* Extra Payload */
        ];
    }

    public function sendToRoleIfStatus($role, $status): bool
    {
        /*$arr = [
            Order::STATUS_NEW => [
                User::ROLE_ADMIN,
                User::ROLE_BRANCH_OWNER,
                User::ROLE_BRANCH_MANAGER,
            ],
            Order::STATUS_PREPARING => [],
            Order::STATUS_WAITING_COURIER => [
                User::ROLE_ADMIN,
            ],
            Order::STATUS_ON_THE_WAY => [
                User::ROLE_USER,
            ],
            Order::STATUS_AT_THE_ADDRESS => [
                User::ROLE_USER,
            ],
            Order::STATUS_DELIVERED => [
                User::ROLE_USER,
            ],
            Order::STATUS_CANCELLED => [
                User::ROLE_ADMIN,
                User::ROLE_BRANCH_OWNER,
                User::ROLE_BRANCH_MANAGER,
                User::ROLE_USER,
            ],
        ];*/

        $availableStatuses = [];
        switch ($role) {
            case User::ROLE_SUPER:
            case User::ROLE_ADMIN:
                $availableStatuses = [
                    Order::STATUS_NEW,
                    Order::STATUS_WAITING_COURIER,
                    Order::STATUS_CANCELLED,
                ];
                break;
            case User::ROLE_BRANCH_MANAGER:
            case User::ROLE_BRANCH_OWNER:
                $availableStatuses = [
                    Order::STATUS_NEW,
                    Order::STATUS_CANCELLED,
                ];
                break;
            case User::ROLE_USER:
                $availableStatuses = [
                    Order::STATUS_ON_THE_WAY,
                    Order::STATUS_AT_THE_ADDRESS,
                    Order::STATUS_DELIVERED,
                    Order::STATUS_CANCELLED,
                ];
                break;
            default;
        }

        return in_array($status, $availableStatuses);
    }
}

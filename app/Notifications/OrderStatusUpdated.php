<?php

namespace App\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Notifications\Channels\OneSignalDashboardChannel;
use App\Notifications\Channels\OneSignalRestaurantChannel;
use Illuminate\Bus\Queueable;
use NotificationChannels\OneSignal\OneSignalChannel;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public Order $order;
    private $roleName;

    /**
     * Create a new notification instance.
     *
     * @param  Order  $order
     * @return void
     */
    public function __construct(Order $order, $roleName, $minutesDelay = 0)
    {
        $this->order = $order;
        if ($roleName == 'super') {
            $roleName = 'admin';
        }
        if ($roleName == 'branch-owner') {
            $roleName = 'branch-manager';
        }
        $this->roleName = $roleName;
        $roleName = str_replace('-', '_', $roleName);

        $this->title = $this->getTitleText();
        foreach (localization()->getSupportedLocalesKeys() as $key) {
            if ($key == 'ku') {
                $key = 'fa';
            }
            $this->body[$key] = $this->getBodyText($roleName, $key, $minutesDelay);
        }
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

        switch ($notifiable->role_name) {
            case User::ROLE_SUPER:
            case User::ROLE_ADMIN:
            case User::ROLE_SUPERVISOR:
            case User::ROLE_AGENT:
            case User::ROLE_CONTENT_EDITOR:
            case User::ROLE_MARKETER:
            case User::ROLE_TRANSLATOR:
                $oneSignalChannelClass = OneSignalDashboardChannel::class;
                break;
            case User::ROLE_BRANCH_OWNER:
            case User::ROLE_BRANCH_MANAGER:
                $oneSignalChannelClass = OneSignalRestaurantChannel::class;
                break;
            default:
                $oneSignalChannelClass = OneSignalChannel::class;
        }
        $via = [
            $oneSignalChannelClass,
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
            'title' => $this->title,
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


    /**
     * Set the notification message.
     *
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    protected function getDeepLink($notifiable)
    {
        if ($notifiable->role_name != User::ROLE_USER) {
            return null;
        }
        $deepLinkKey = null;
        if (in_array($this->order->status, [
            Order::STATUS_ON_THE_WAY,
        ])) {
            $deepLinkKey = 'order_tracking';
        } elseif (in_array($this->order->status, [
            Order::STATUS_DELIVERED,
        ])) {
            $deepLinkKey = 'order_rating';
        }

        if ( ! is_null($deepLinkKey)) {
            return Controller::generateDeepLink($deepLinkKey, [
                'id' => $this->order->id,
                'channel' => $this->order->is_grocery ? config('app.app-channels.grocery') : config('app.app-channels.food')
            ]);
        }

        return null;
    }

    /**
     * @param $roleName
     * @param $key
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    private function getBodyText($roleName, $locale, $minutesDelay)
    {
        if ($minutesDelay > 0) {
            return '🚨 '.trans("notifications.order_status_updated_for_user_{$roleName}_{$this->order->status}_minutes_delay",
                    [
                        'number' => ("({$this->order->reference_code})"),
                        'minutes' => $minutesDelay,
                    ], $locale);
        } else {
            return '🛵 '.trans("notifications.order_status_updated_for_user_{$roleName}_{$this->order->status}",
                    [
                        'number' => ("({$this->order->reference_code})"),
                        'branchName' => optional($this->order->branch)->title,
                    ], $locale);
        }
    }

    private function getTitleText()
    {
        if ($this->order->type === Order::CHANNEL_GROCERY_OBJECT) {
            $emojies = ['🍓', '🍫', '🍎', '🍍', '🧀', '🥩', '🥝', '🍞', '🥬', '🥑', '🍌', '🍋', '🥫'];
        } else/*if ($this->order->type === Order::CHANNEL_FOOD_OBJECT)*/ {
            $emojies = ['🍔', '🍕', '🌭', '🌮', '🌯', '🥙', '🥗', '🍔', '🥪', '🍕', '🍟', '🍖', '🍗', '🍔', '🍲', '🍕'];
        }
        $randomEmoji = $emojies[mt_rand(0, count($emojies) - 1)];
        $text = $randomEmoji.' ';

        if ( ! is_null($this->order->chain)) {
            $text .= $this->order->chain->title;
        }

        return $text;
    }
}

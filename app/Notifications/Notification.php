<?php

namespace App\Notifications;

use App\Models\User;
use App\Notifications\Channels\OneSignalDashboardChannel;
use App\Notifications\Channels\OneSignalRestaurantChannel;
use Illuminate\Notifications\Notification as BaseNotification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class Notification extends BaseNotification
{
    const ACTION_ORDER = 'open-order';

    const TAG_ADS = 'ads';
    const TAG_ORDER_STATUS = 'order-status';

    protected $className;
    protected $action;
    protected $tag;

    // Payload Related
    protected $object;
    protected $subject;

    /* View Related */
    protected $title;
//    protected $subTitle;
    protected $body;

    /* Web Navigation Related */
    protected $routeName;
    protected $routeVariables;
    protected $routeParams;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        // Todo: send the notification based on the user preference, remember that we are using $this->tag for OneSignal related subscriptions
        // $notifiable->settings
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
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     * @throws \Throwable
     */
    /*public function toMail($notifiable)
    {
        return (new MailMessage)
            ->markdown('emails.notification')
            ->subject(config('app.name').' - '.trans('strings.have-new-notification'))
            ->success()
            ->greeting(trans('strings.have-new-notification').'!')
            ->line($this->getTitle())
            ->action(trans('strings.notifications_related.all-notifications'), route('notifications.index'));
    }*/

    /**
     * @return mixed
     */
    protected function getClassName()
    {
        if ( ! $this->className) {
            $class = explode('\\', get_class($this));
            $this->className = end($class);
        }

        return $this->className;
    }

    /**
     * Set the notification message.
     *
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    protected function getTitle()
    {
        if ( ! $this->title) {
            $this->title = trans('strings.notifications_related.'.$this->getClassName().'.message', [
                'user' => $this->subject->name,
                'diary' => $this->object->title,
            ]);
        }

        return $this->title;
    }

    /**
     * Set the notification message.
     *
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    protected function getDeepLink($notifiable)
    {
        return null;
    }

    public function toOneSignal($notifiable)
    {
        try {
            $oneSignalMessage = OneSignalMessage::create();

            if ($this->title) {
                $oneSignalMessage->setSubject($this->title);
            }
            if ($this->body) {
                $oneSignalMessage->setBody($this->body);
            }

            // Branch owners & managers
            if ($notifiable->role_name == User::ROLE_BRANCH_OWNER || $notifiable->role_name == User::ROLE_BRANCH_MANAGER) {
                $oneSignalMessage->setParameter('android_channel_id',
                    config('services.onesignal.restaurant_app_android_channel_id'))
                                 ->setParameter('ios_sound', 'new_notify.wav')
                                 ->setParameter('android_sound', 'new_notify');
            } else {
                $oneSignalMessage->setParameter('android_channel_id',
                    config('services.onesignal.customer_app_android_channel_id'))
                                 ->setParameter('ios_sound', 'new_notify.wav')
                                 ->setParameter('android_sound', 'new_notify');
            }

            if ( ! is_null($this->getDeepLink($notifiable))) {
                $oneSignalMessage->setData('deep_link', $this->getDeepLink($notifiable));
            }

            /*if ($this->tag) {
                $oneSignalMessage->setParameter('filters', [
                    [
                        'field' => 'tag',
                        'key' => $this->tag,
                        'relation' => '=',
                        'value' => true
                    ]
                ]);
            }*/

            return $oneSignalMessage;
        } catch (\Exception $e) {
            info('error @toOneSignal in OrderStatusUpdated', [
                'order' => $this->order,
                'user' => $notifiable,
                'exception' => $e,
            ]);
        }
    }

}

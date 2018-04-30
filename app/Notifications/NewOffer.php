<?php

namespace App\Notifications;

use App\User;
use App\Offer;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Notifications\Messages\MailMessage;
class NewOffer extends Notification implements ShouldBroadcastNow
{


    protected $user;
    protected $offer;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    /**
     * NewOffer constructor.
     * @param User $user
     * @param Offer $offer
     */
    public function __construct(User $user, Offer $offer)
    {
        $this->user = $user;
        $this->offer = $offer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
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
            'id' => $this->id,
            'read_at' => null,
            'data' => [
                'offer_id' => $this->offer->id,
                'service' => $this->offer->service->name,
                'service_id' => $this->offer->service->id,
                'user' => $this->user->fname . ' ' . $this->user->name,
                'image' => $this->user->picture_thumb
            ],
        ];
    }
    public function toDatabase($notifiable)
    {
        return [
            'offer_id' => $this->offer->id,
            'service' => $this->offer->service->name,
            'service_id' => $this->offer->service->id,
            'user' => $this->user->fname . ' ' . $this->user->name,
            'image' => $this->user->picture_thumb
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'read_at' => null,
            'data' => [
                'offer_id' => $this->offer->id,
                'service' => $this->offer->service->name,
                'service_id' => $this->offer->service->id,
                'user' => $this->user->fname . ' ' . $this->user->name,
                'image' => $this->user->picture_thumb
            ],
        ]);
    }
}

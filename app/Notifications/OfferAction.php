<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use App\Offer;
use App\User;
class OfferAction extends Notification
{
    use Queueable;

    protected $user;
    protected $offer;
    protected $action;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    /**
     * OfferAction constructor.
     * @param User $user
     * @param Offer $offer
     */
    public function __construct(User $user, Offer $offer, $action)
    {
        $this->action = $action;
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
                'request_id' => $this->offer->request->id,
                'service' => $this->offer->service->name,
                'service_id' => $this->offer->service->id,
                'user' => $this->user->fname . ' ' . $this->user->name,
                'image' => $this->user->picture_thumb,
                'action' => $this->action
            ],
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'read_at' => null,
            'data' => [
                'offer_id' => $this->offer->id,
                'request_id' => $this->offer->request->id,
                'service' => $this->offer->service->name,
                'service_id' => $this->offer->service->id,
                'user' => $this->user->fname . ' ' . $this->user->name,
                'image' => $this->user->picture_thumb,
                'action' => $this->action
            ],
        ]);
    }
    public function toDatabase($notifiable)
    {
        return [
            'offer_id' => $this->offer->id,
            'request_id' => $this->offer->request->id,
            'service' => $this->offer->service->name,
            'service_id' => $this->offer->service->id,
            'user' => $this->user->fname . ' ' . $this->user->name,
            'image' => $this->user->picture_thumb,
            'action' => $this->action
        ];
    }
}

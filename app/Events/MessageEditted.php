<?php

namespace App\Events;

use App\Message;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageEditted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    protected $user;
    protected $message;
    protected $receiver;
    protected $action;
    public function __construct(User $user, User $receiver, Message $message, $action)
    {
        //
        $this->user = $user;
        $this->receiver = $receiver;
        $this->message = $message;
        $this->action = $action;
    }
    public function broadcastWith()
    {
        return [
            'message' => $this->message->toArray(),
            'action' => $this->action
            ];

    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.'. $this->receiver->id .'.chat');
    }
}

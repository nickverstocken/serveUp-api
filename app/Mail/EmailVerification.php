<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Mailable implements ShouldQueue
{
    protected $user;
    protected $verification_code;
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $verification_code)
    {
        $this->verification_code = $verification_code;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->from(('FROM_EMAIL_ADDRESS'), env('Serve Up'))
            ->subject('Welkom bij Serve-Up!')
            ->view('email.verify', ['user' => $this->user, 'verification_code' => $this->verification_code]);

        return $this;
    }
}

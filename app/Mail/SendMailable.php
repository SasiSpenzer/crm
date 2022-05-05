<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailable extends Mailable
{
    use Queueable, SerializesModels;
    public $full_name;
    public $username;
    public $password;
    public $validated_text;

    /**
     * SendMailable constructor.Create a new message instance.
     * @param $full_name
     * @param $username
     * @param $password
     * @param $validated_text
     */
    public function __construct($full_name, $username, $password, $validated_text)
    {
        $this->full_name = $full_name;
        $this->username = $username;
        $this->password = $password;
        $this->validated_text = $validated_text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.password_reset')->subject('Your Account Details')
            ->with([
                'full_name' => $this->full_name,
                'username' => $this->username,
                'password' => $this->password,
                'validated_text' => $this->validated_text,
            ]);
    }
}
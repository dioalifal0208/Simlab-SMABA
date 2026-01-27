<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public string $code;

    /**
     * @var string
     */
    public string $name;

    /**
     * @var int
     */
    public int $expiresMinutes;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code, string $name, int $expiresMinutes = 10)
    {
        $this->code = $code;
        $this->name = $name;
        $this->expiresMinutes = $expiresMinutes;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Kode OTP Login Lab SMABA')
            ->view('emails.login-otp');
    }
}

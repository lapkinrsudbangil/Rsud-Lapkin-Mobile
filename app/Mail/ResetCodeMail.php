<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $expiresInMinutes;

    public function __construct($code, $expiresInMinutes = 15)
    {
        $this->code = $code;
        $this->expiresInMinutes = $expiresInMinutes;
    }

    public function build()
    {
        return $this->subject('Kode Verifikasi Reset Password')
                    ->view('emails.reset_code')
                    ->with([
                        'code' => $this->code,
                        'expiresInMinutes' => $this->expiresInMinutes,
                    ]);
    }
}

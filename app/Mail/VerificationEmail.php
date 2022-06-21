<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('message.code_Verification'))
            ->view('emails.verification');
//        return $this->markdown('emails.verification');
    }

//$data['email_code'] = rand(1000, 9999);
//$user = User::query()->create($data);
//Mail::to($user->email)->send(new AccountConfirmationMail(['name' => $user->username, 'code' => $user->email_code]));
}

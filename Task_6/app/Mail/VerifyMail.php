<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;
    private $id,$email,$name,$token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userId,$userEmail,$userName,$userToken)
    {
        $this->id = $userId;
        $this->email = $userEmail;
        $this->name = $userName;
        $this->token = $userToken;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Verify Mail',
        );
    }
//
    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown : ('verificationEmail'),
            with: [
                'uId' => $this->id,
                'uEmail' => $this->email,
                'uName' => $this->name,
                'uToken' => $this->token
            ]
        );
    }
//
//    /**
//     * Get the attachments for the message.
//     *
//     * @return array
//     */
//    public function attachments()
//    {
//        return [];
//    }

//    /**
//     * @return $this
//     */
//    public function build(){
//        return $this->markdown(
//            'verificationEmail');
//        ['name' => $this->user->name]);
//    }
}

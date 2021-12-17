<?php


namespace App\Mail\App;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendApplicantCustomMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $template;

    public $subject;

    public function __construct($data)
    {
        $this->template = $data->mail;
        $this->subject = $data->subject;
    }

    public function build()
    {
        return $this->view('notification.template', [
            'template' => $this->template
        ])->subject($this->subject);
    }
}
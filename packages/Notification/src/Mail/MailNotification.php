<?php
namespace Encoda\Notification\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNotification extends Mailable
{
    use Queueable, SerializesModels;


}

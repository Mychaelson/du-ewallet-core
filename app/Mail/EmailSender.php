<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailSender extends Mailable
{
    use Queueable, SerializesModels;

    protected $data = [];
    protected $template;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $template = 'mail.default')
    {
      $this->data = $data;
      $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $data['user'] = [
        'name' => 'mr. sparkle',
        'email' => 'email@sample.com',
      ];

      $this->data = array_merge($this->data, $data);

      return $this->subject($this->data['title'])
            ->view('welcome', (array)$this->data);

      // return $this->subject($this->data['title'])
      //       ->view($this->template, (array)$this->data);
    }
}

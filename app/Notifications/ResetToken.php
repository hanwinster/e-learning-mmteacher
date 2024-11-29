<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetToken extends Notification
{
    use Queueable;

	public $token;
	
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $isGmail = ( strpos($notifiable->email, "gmail.com") || strpos($notifiable->email, "googlemail.com") ) ? true : false;
        if($isGmail) {
            return (new MailMessage)
            //->line('The introduction to the notification.')
            //->action('Notification Action', url('/'))
            //->line('Thank you for using our application!');
            ->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
            ->subject('Please reset your account!')
            ->markdown('vendor.mail.markdown.reset_token', ['token' => $this->token]);
        } else {
            return (new MailMessage)
            //->line('The introduction to the notification.')
            //->action('Notification Action', url('/'))
            //->line('Thank you for using our application!');
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->subject('Please reset your account!')
            ->markdown('vendor.mail.markdown.reset_token', ['token' => $this->token]);
        }
        
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

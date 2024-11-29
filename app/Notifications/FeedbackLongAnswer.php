<?php

namespace App\Notifications;

use App\Models\LongAnswerUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Channels\DatabaseChannel;

class FeedbackLongAnswer extends Notification
{
    use Queueable;

    private $laUser;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($laUser)
    {
        $this->laUser = $laUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DatabaseChannel::class, 'mail'];
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
            ->from(env('MAIL_FROM_ADDRESS'), env('APP_NAME'))
            ->line('The instructor returned feedback for your assignment')
            ->action('Notification Action', route('quiz.show', $this->laUser->long_answer->question->quiz->id))
            ->line('Thank you for using our application!');
        } else {
            return (new MailMessage)
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->line('The instructor returned feedback for your assignment')
            ->action('Notification Action', route('quiz.show', $this->laUser->long_answer->question->quiz->id))
            ->line('Thank you for using our application!');
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
            'title' => $this->laUser->commentUser->name . ' return feedback for ' . 
            strip_tags($this->laUser->long_answer->question->title) . ' for ' . 
            strip_tags($this->laUser->long_answer->question->quiz->course->title) . ' course',
            'body' => $this->laUser->commentUser->name . ' return feedback for ' . 
            strip_tags($this->laUser->long_answer->question->title) . ' for ' . 
            strip_tags($this->laUser->long_answer->question->quiz->course->title) . ' course',
            'model_id' => $this->laUser->id,
            'click_action_link' => route('quiz.show', $this->laUser->long_answer->question->quiz->id)
        ];
    }
}

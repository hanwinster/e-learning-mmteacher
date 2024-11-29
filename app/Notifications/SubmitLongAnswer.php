<?php

namespace App\Notifications;

use App\Models\LongAnswer;
use App\Models\LongAnswerUser;
use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Channels\DatabaseChannel;

class SubmitLongAnswer extends Notification
{
    use Queueable;

    private $longAnswerUser;
    private $courseId;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(LongAnswerUser $longAnswerUser, $courseId)
    {
        $this->longAnswerUser = $longAnswerUser;
        $this->courseId = $courseId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DatabaseChannel::class,'mail'];
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
                    ->line($this->longAnswerUser->user->name . ' submitted long answer for ' . strip_tags($this->longAnswerUser->long_answer->question->title))
                    ->action('Notification Action', route('member.long_answer.detail', [$this->longAnswerUser->long_answer_id, $this->courseId]))
                    ->line('Thank you for using our application!');
        } else {
            return (new MailMessage)
                    ->from(env('MAIL_USERNAME'), env('APP_NAME'))
                    ->line($this->longAnswerUser->user->name . ' submitted long answer for ' . strip_tags($this->longAnswerUser->long_answer->question->title))
                    ->action('Notification Action', route('member.long_answer.detail', [$this->longAnswerUser->long_answer_id, $this->courseId]))
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
            //
        ];
    }

    public function toDatabase()
    {
        return [
            // 'title' => $this->longAnswerUser->user->name . ' submitted ' . $this->longAnswerUser->assignment->title . ' for ' . $this->longAnswerUser->assignment->course->title . 'course',
            // 'body' => $this->longAnswerUser->user->name . ' submitted ' . $this->longAnswerUser->assignment->title . ' for ' . $this->longAnswerUser->assignment->course->title . 'course',
            'title' => $this->longAnswerUser->user->name . ' submitted ' . strip_tags($this->longAnswerUser->long_answer->question->title),
            'body' => $this->longAnswerUser->user->name . ' submitted ' . strip_tags($this->longAnswerUser->long_answer->question->title),
            'model_id' => $this->longAnswerUser->id,
            'click_action_link' => route('member.long_answer.detail', [$this->longAnswerUser->long_answer_id, $this->courseId])
        ];
    }
}

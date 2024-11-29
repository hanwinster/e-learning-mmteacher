<?php

namespace App\Notifications;

use App\Models\LongAnswer;
use App\Models\AssessmentUser;
use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Channels\DatabaseChannel;

class SubmitAssessmentLongAnswer extends Notification
{
    use Queueable;

    private $assessmentUser;
    private $courseId;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($assessmentUser, $courseId)
    {
        $this->assessmentUser = $assessmentUser;
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
                    ->line($this->assessmentUser->user->name . ' submitted long answer for ' . strip_tags($this->assessmentUser->assessment_question_answer->question))
                    ->action('Notification Action', route('member.course.assessment-qa.detail', [$this->assessmentUser->assessment_question_answer_id, $this->courseId]))
                    ->line('Thank you for using our application!');
        } else {
            return (new MailMessage)
                    ->from(env('MAIL_USERNAME'), env('APP_NAME'))
                    ->line($this->assessmentUser->user->name . ' submitted long answer for ' . strip_tags($this->assessmentUser->assessment_question_answer->question))
                    ->action('Notification Action', route('member.course.assessment-qa.detail', [$this->assessmentUser->assessment_question_answer_id, $this->courseId]))
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
            // 'title' => $this->assessmentUser->user->name . ' submitted ' . $this->assessmentUser->assignment->title . ' for ' . $this->assessmentUser->assignment->course->title . 'course',
            // 'body' => $this->assessmentUser->user->name . ' submitted ' . $this->assessmentUser->assignment->title . ' for ' . $this->assessmentUser->assignment->course->title . 'course',
            'title' => $this->assessmentUser->user->name . ' submitted ' . strip_tags($this->assessmentUser->assessment_question_answer->question),
            'body' => $this->assessmentUser->user->name . ' submitted ' . strip_tags($this->assessmentUser->assessment_question_answer->question),
            'model_id' => $this->assessmentUser->id,
            'click_action_link' => route('member.course.assessment-qa.detail', [$this->assessmentUser->assessment_question_answer_id, $this->courseId])
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\CourseApprovalRequest;
use App\Channels\DatabaseChannel;

class NotifyAllCourseTakers extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subject;
    protected $message;
    protected $course;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($course, $subject, $message)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->course = $course;
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
            ->subject($this->subject)
            ->markdown('vendor.mail.markdown.notify-all-course-takers', [ 'message' => $this->message ]);
        } else {
            return (new MailMessage)
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->subject($this->subject)
            ->markdown('vendor.mail.markdown.notify-all-course-takers', [ 'message' => $this->message ]);
        }
        
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->subject,
            'body' => $this->message,
            'model_id' => $this->course->id,
            'click_action_link' => route('courses.show', [$this->course])
        ];
    }
}

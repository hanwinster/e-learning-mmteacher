<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Course;
use App\Channels\DatabaseChannel;

class NewCoursePosted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $course;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Course $course)
    {
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
            ->subject('New course - "' . strip_tags($this->course->title ). '" was posted.')
            ->line('A New course named "' . strip_tags($this->course->title) . '" , which is in the same category of the courses you have taken, was posted.')
            ->line('Course Title: ' . strip_tags($this->course->title) )
            ->action('View', route('courses.show', $this->course))
            ->line('Thank you for using our application!');
        } else {
            return (new MailMessage)
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->subject('New course - "' . strip_tags($this->course->title ). '" was posted.')
            ->line('A New course named "' . strip_tags($this->course->title) . '" , which is in the same category of the courses you have taken, was posted.')
            ->line('Course Title: ' . strip_tags($this->course->title) )
            ->action('View', route('courses.show', $this->course))
            ->line('Thank you for using our application!');
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
            'title' => 'New course - "' . strip_tags($this->course->title) . '" was posted',
            'body' => 'A New course named "' . strip_tags($this->course->title) . '" , which is in the same category of the courses you have taken, was posted.',
            'click_action_link' => route('courses.show', $this->course),
            'click_action_page' => 'App\Models\Course',
            'model_id' => $this->course->id,
            'line' => 'Thank you for using our application!'
        ];
    }
}

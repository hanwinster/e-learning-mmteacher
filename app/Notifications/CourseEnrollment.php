<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Course;
use App\Models\CourseLearner;
use App\Channels\DatabaseChannel;

class CourseEnrollment extends Notification implements ShouldQueue
{
    use Queueable;

    protected $enrolledArr;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Array $enrolledArr)
    {
        $this->enrolledArr = $enrolledArr;
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
            ->line('The user named "' . $this->enrolledArr['courseTaker'] . '" enrolled for your course named ' 
                . strip_tags($this->enrolledArr['courseTitle']) )
            ->line('User Type:  '.$this->enrolledArr['userType'])
            ->line('User Email: ' . $this->enrolledArr['courseTakerEmail'])
            ->action('View Course', route('courses.show', $this->enrolledArr['course']))
            ->line('Thank you for using our application!');
        } else {
            return (new MailMessage)
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->line('The user named "' . $this->enrolledArr['courseTaker'] . '" enrolled for your course named ' 
                . strip_tags($this->enrolledArr['courseTitle']) )
            ->line('User Type:  '.$this->enrolledArr['userType'])
            ->line('User Email: ' . $this->enrolledArr['courseTakerEmail'])
            ->action('View Course', route('courses.show', $this->enrolledArr['course']))
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
            'title' => 'The user named "' . $this->enrolledArr['courseTaker'] . '" enrolled for your course named ' 
            . strip_tags($this->enrolledArr['courseTitle']),
            'body' => 'User Type:  '.$this->enrolledArr['userType'] . 'User Email: ' . $this->enrolledArr['courseTakerEmail'],
            'click_action_link' => route('courses.show', $this->enrolledArr['course']),
            'click_action_page' => 'App\Models\Course',
            'model_id' => $this->enrolledArr['id'],
            'line' => 'Thank you for using our application!'
        ];
    }
}

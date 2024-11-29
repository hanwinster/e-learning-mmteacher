<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\RatingReview;
use App\Channels\DatabaseChannel;

class CourseReviewSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $courseReviewArray;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Array $courseReviewArray)
    {
        $this->courseReviewArray = $courseReviewArray;
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
            ->line('The course taker "' . $this->courseReviewArray['courseReviewer'] . '" submitted the review and rating for ' 
                . strip_tags($this->courseReviewArray['courseTitle']) )
            ->line('Rating:  '.$this->courseReviewArray['rating'])
            ->line('Review: ' . $this->courseReviewArray['remark'])
            ->action('View Course', route('courses.show', $this->courseReviewArray['course']))
            ->line('Thank you for using our application!');
        } else {
            return (new MailMessage)
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->line('The course taker "' . $this->courseReviewArray['courseReviewer'] . '" submitted the review and rating for ' 
                . strip_tags($this->courseReviewArray['courseTitle']) )
            ->line('Rating:  '.$this->courseReviewArray['rating'])
            ->line('Review: ' . $this->courseReviewArray['remark'])
            ->action('View Course', route('courses.show', $this->courseReviewArray['course']))
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
            'title' => 'The course taker "' . $this->courseReviewArray['courseReviewer'] . '" submitted the review and rating for ' 
                        . strip_tags($this->courseReviewArray['courseTitle']),
            'body' => 'Rating: ' . $this->courseReviewArray['rating'] . '  Review: ' . $this->courseReviewArray['remark'],
            'click_action_link' => route('courses.show', $this->courseReviewArray['course']),
            'click_action_page' => 'App\Models\RatingReview',
            'model_id' => $this->courseReviewArray['id'],
            'line' => 'Thank you for using our application!'
        ];
    }
}

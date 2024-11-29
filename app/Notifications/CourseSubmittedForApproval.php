<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\CourseApprovalRequest;
use App\Channels\DatabaseChannel;

class CourseSubmittedForApproval extends Notification implements ShouldQueue
{
    use Queueable;

    protected $courseApprovalRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CourseApprovalRequest $courseApprovalRequest)
    {
        $this->courseApprovalRequest = $courseApprovalRequest;
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
            ->line('A course "' . strip_tags($this->courseApprovalRequest->course->title) . '" was submitted for approval by ' . 
                $this->courseApprovalRequest->user->name . '.')
            ->line('Message: ')
            ->line('"Message: ' . $this->courseApprovalRequest->description . '"')
            ->action('View', route('member.course-approval-request.show', $this->courseApprovalRequest->id))
            ->line('Thank you for using our application!');
        } else {
            return (new MailMessage)
            ->from(env('MAIL_USERNAME'), env('APP_NAME'))
            ->line('A course "' . strip_tags($this->courseApprovalRequest->course->title) . '" was submitted for approval by ' . 
                $this->courseApprovalRequest->user->name . '.')
            ->line('Message: ')
            ->line('"Message: ' . $this->courseApprovalRequest->description . '"')
            ->action('View', route('member.course-approval-request.show', $this->courseApprovalRequest->id))
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
            'title' => 'A course "' . strip_tags($this->courseApprovalRequest->course->title) . '" was submitted for approval by ' . $this->courseApprovalRequest->user->name . '.',
            'body' => 'A course "' . strip_tags($this->courseApprovalRequest->course->title) . '" was submitted for approval by ' . $this->courseApprovalRequest->user->name . '.',
            'click_action_link' => route('member.course-approval-request.show', $this->courseApprovalRequest->id),
            'click_action_page' => 'App\Models\CourseApprovalRequest',
            'model_id' => $this->courseApprovalRequest->id,
            'line' => 'Thank you for using our application!'
        ];
    }
}

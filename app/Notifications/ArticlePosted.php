<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Article;
use App\Channels\DatabaseChannel;

class ArticlePosted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $article;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
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
        return (new MailMessage)
                    ->subject('Article: ' . $this->article->title . ' was posted by ' . $this->article->user->name . '.')
                    ->line('Article: ' . $this->article->title . ' was posted by ' . $this->article->user->name . '.')
                    ->action('View', route('article.show', $this->article->slug))
                    ->line('Thank you for using our application!');
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
            'title' => 'Article: ' . $this->article->title . ' was posted by ' . $this->article->user->name . '.',
            'body' => 'Article: ' . $this->article->title . ' was posted by ' . $this->article->user->name . '.',
            'click_action_link' => route('article.show', $this->article->slug),
            'click_action_page' => 'Article',
            'model_id' => $this->article->id,
            'line' => 'Thank you for using our application!'
        ];
    }
}

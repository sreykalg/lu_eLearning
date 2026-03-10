<?php

namespace App\Notifications;

use App\Models\Discussion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MentionInDiscussionNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $mentionerName,
        public Discussion $discussion,
        public ?int $replyId = null,
        public string $excerpt = ''
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('discussions.index') . '?highlight=' . $this->discussion->id;
        return (new MailMessage)
            ->subject($this->mentionerName . ' mentioned you in a discussion')
            ->line($this->mentionerName . ' mentioned you in a discussion.')
            ->action('View discussion', $url);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'mentioner_name' => $this->mentionerName,
            'mentioner_id' => $this->discussion->user_id,
            'discussion_id' => $this->discussion->id,
            'reply_id' => $this->replyId,
            'excerpt' => $this->excerpt,
            'url' => route('discussions.index') . '?highlight=' . $this->discussion->id,
        ];
    }
}

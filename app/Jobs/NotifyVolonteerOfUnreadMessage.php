<?php

namespace App\Jobs;

use App\Models\Message;
use App\Models\Participation;
use App\Notifications\VolunteerHasUnreadMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyVolunteerOfUnreadMessage implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Message $message)
    {
        $this->onQueue('low-tasks');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $conversation = $this->message->conversation;
        $lastMessage = $conversation->latestMessage;
        $conversable = $conversation->conversable;

        if ($lastMessage->id !== $this->message->id) {
            return;
        }

        if ($conversable::class !== Participation::class || !in_array($conversable->state, ['En attente de validation', 'En cours de traitement'])) {
            return;
        }

        $volunteerReadAt = $conversation->users()
            ->where('conversations_users.user_id', $conversable->profile->user->id)
            ->first()->pivot->read_at;

        if (!$volunteerReadAt || $volunteerReadAt < $lastMessage->updated_at) {
            $conversable->profile->user->notify(new VolunteerHasUnreadMessage($this->message));
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\Participation;
use Illuminate\Console\Command;

class FillConversationResponseTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:fill-conversation-response-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill conversation response time if null';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $conversations = Conversation::whereNull('response_time')
        ->get();

        $this->info($conversations->count() . ' conversations will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($conversations as $conversation) {
                $participation = $conversation->conversable;
                // Si participation validée ou refusée on prend, sinon on check les derniers messages
                if ($participation) {
                    $participationResponseTime = $lastMessageResponseTime = null;

                    // RESPONSE TIME WITH PARTICIPATION CHANGED STATE
                    if (in_array($participation->state, ['Validée', 'Refusée'])) {
                        $participationResponseTime = $participation->updated_at->timestamp - $participation->created_at->timestamp;
                    }

                    // RESPONSE TIME WITH LAST MESSAGE BY RESPONSABLE
                    $lastMessageFromResponsable = $conversation->messages->where('from_id', '!=', $participation->profile->user->id)->first();
                    if ($lastMessageFromResponsable) {
                        $lastMessageResponseTime = $lastMessageFromResponsable->created_at->timestamp - $participation->created_at->timestamp;
                    }

                    if (isset($participationResponseTime) && !isset($lastMessageResponseTime)) {
                        $this->info("Conversation #{$conversation->id} -> Setting response time {$participationResponseTime} from participation");
                        $conversation->response_time = $participationResponseTime;
                    } elseif (!isset($participationResponseTime) && isset($lastMessageResponseTime)) {
                        $this->info("Conversation #{$conversation->id} -> Setting response time {$lastMessageResponseTime} from message");
                        $conversation->response_time = $lastMessageResponseTime;
                    } elseif (isset($participationResponseTime) && isset($lastMessageResponseTime)) {
                        $this->info("Conversation #{$conversation->id} -> Compare participation response time {$participationResponseTime} to message response time {$lastMessageResponseTime}");
                        $conversation->response_time = $lastMessageResponseTime < $participationResponseTime ? $lastMessageResponseTime : $participationResponseTime;
                    } else {
                        $lastMessageFromResponsable = $conversation->messages->where('from_id', '!=', $participation->profile->user->id)->first();
                        if ($lastMessageFromResponsable) {
                            $conversation->response_time = $lastMessageFromResponsable->created_at->timestamp - $participation->created_at->timestamp;
                            $conversation->saveQuietly(); // No observer
                        } else {
                            $this->warn("Cant determine response time for participation : {$participation->id}");
                        }
                    }
                } else {
                    $this->warn("Conversation : {$conversation->id} / Participation {$conversation->conversable_id} has no participation linked (-> deleted)");
                    $conversation->delete();
                }
            }
        }
    }
}

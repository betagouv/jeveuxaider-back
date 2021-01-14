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
        $query = Conversation::whereNull('response_time');
        $this->info($query->count() . ' conversations will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            Conversation::whereNull('response_time')->chunkById(5000, function ($conversations) {
                $conversations->each(function ($conversation, $key) {
                    $participation = $conversation->conversable;
                    if ($participation) {
                        $participationResponseTime = $lastMessageResponseTime = null;
                        // Response time when participation state changed
                        if (in_array($participation->state, ['Validée', 'Refusée'])) {
                            $participationResponseTime = $participation->updated_at->timestamp - $participation->created_at->timestamp;
                        }
                        // Response time with last message by responsable
                        $lastMessageFromResponsable = $conversation->messages->where('from_id', '!=', $participation->profile->user->id)->first();
                        if (isset($lastMessageFromResponsable)) {
                            $lastMessageResponseTime = $lastMessageFromResponsable->created_at->timestamp - $participation->created_at->timestamp;
                        }

                        if ($participationResponseTime && $lastMessageResponseTime) {
                            $conversation->response_time = min([$participationResponseTime, $lastMessageResponseTime]);
                        } elseif ($participationResponseTime) {
                            $conversation->response_time = $participationResponseTime;
                        } elseif ($lastMessageResponseTime) {
                            $conversation->response_time = $lastMessageResponseTime;
                        }

                        $conversation->saveQuietly();
                    } else {
                        $this->warn("Conversation : {$conversation->id} / Participation {$conversation->conversable_id} has no participation linked (-> deleted)");
                        $conversation->delete();
                    }
                });
            });
        }
    }
}

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
                // Si participation updated on prend, sinon on check les derniers messages
                if ($participation) {
                    if ($participation->created_at != $participation->updated_at) {
                        $conversation->response_time = $participation->updated_at->timestamp - $participation->created_at->timestamp;
                        $conversation->saveQuietly(); // No observer
                    } else {
                        $lastMessageFromResponsable = $conversation->messages->where('from_id', '!=', $participation->profile->user->id)->first();
                        if ($lastMessageFromResponsable) {
                            $conversation->response_time = $lastMessageFromResponsable->created_at->timestamp - $participation->created_at->timestamp;
                            $conversation->saveQuietly(); // No observer
                        }
                    }
                } else {
                    $this->warn("Conversation : {$conversation->id} / Participation {$conversation->conversable_id} has no participation linked (deleted?)");
                    $this->info("Conversation : {$conversation->id} has been deleted !");
                    $conversation->delete();
                }
            }
        }
    }
}

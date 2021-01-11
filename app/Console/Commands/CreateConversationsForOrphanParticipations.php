<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\Participation;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class CreateConversationsForOrphanParticipations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:create-conversations-for-orphan-participations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create conversations for orphan participations';

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
        $participations = Participation::whereDoesntHave('conversation')
            ->whereHas('mission', function (Builder $query) {
                $query
                    ->whereNull('deleted_at')
                    // ->whereHas('structure', function (Builder $query) {
                    //     $query->whereNull('deleted_at');
                    // })
                    ;
            })
            //->limit(1)
            ->get();

        $this->info($participations->count() . ' conversations will be created');
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($participations as $participation) {
                if ($participation->mission && $participation->mission->structure) {
                    $benevoleUser = $participation->profile->user;
                    $responsableUser = $participation->mission->responsable->user ?? $participation->mission->structure->user;
                    $conversation = new Conversation;
                    if ($participation->state != 'En attente de validation') {
                        $conversation->response_time = $participation->updated_at->timestamp - $participation->created_at->timestamp;
                    }
                    $conversation->conversable()->associate($participation);
                    $conversation->save();
                    $conversation->users()->attach([$benevoleUser->id, $responsableUser->id]);
                } else {
                    $this->error("Participation : {$participation->id} mission or structure has been deleted");
                }
            }
        }
    }
}

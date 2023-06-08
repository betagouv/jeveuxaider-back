<?php

namespace App\Jobs;

use App\Models\Mission;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;
use App\Models\Message;

class MissionCloseAlreadyOutdatedJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mission;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($missionId)
    {
        $this->mission = Mission::find($missionId);
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()?->cancelled()) {
            return;
        }
        if (!$this->mission) {
            return;
        }
        if ($this->mission->state !== 'Validée') {
            return;
        }
        if (!($this->mission->end_date < Carbon::now())) {
            return;
        }

        $this->mission->state = 'Terminée';
        $this->mission->saveQuietly();

        if ($this->mission->responsable) {
            $this->mission->participations->whereIn('state', ['En attente de validation', 'En cours de traitement'])
                ->each(function ($participation) {
                    $participation->state = 'Refusée';
                    $participation->saveQuietly();

                    activity()
                        ->performedOn($participation)
                        ->withProperties([
                                'attributes' => ['state' => 'Refusée'],
                                'old' => ['state' => $participation->state]
                            ])
                        ->event('updated')
                        ->log('updated');

                    $participation->load('conversation');
                    if ($participation->conversation) {
                        (new Message([
                            'conversation_id' => $participation->conversation->id,
                            'type' => 'contextual',
                            'content' => 'La participation a été déclinée',
                            'contextual_state' => 'Refusée',
                            'contextual_reason' => 'mission_terminated',
                        ]))->saveQuietly();
                    }
                });
            if ($this->mission->end_date > Carbon::now()->subMonth(6)) {
                $this->mission->sendNotificationsTemoignages();
            }
        }
    }
}

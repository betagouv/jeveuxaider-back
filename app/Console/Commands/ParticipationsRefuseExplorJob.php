<?php

namespace App\Console\Commands;

use App\Models\Participation;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ParticipationsRefuseExplorJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participations-refuse-explorjob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Refuse les participations en cours de traitement car le bénévole ne répond pas";

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Participation::ofStructure(6144)
            ->where('state', 'En cours de traitement')
            ->where('updated_at', '<=' , new Carbon('24 march 2023 23:59:59'));

        if ($this->confirm("Refuser les participations en cours de traitement de l'organisation ExplorJob ? " . $query->count() . ' participations vont être mises à jour.')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $participation) {
                if ($participation->conversation && $participation->mission->responsable) {
                    $participation->conversation->messages()->create([
                        'from_id' => $participation->mission->responsable->user->id,
                        'type' => 'contextual',
                        'content' => 'La participation a été déclinée',
                        'contextual_state' => 'Refusée',
                        'contextual_reason' => 'no_response',
                    ]);

                    if ($participation->mission->responsable) {
                        $participation->mission->responsable->user->conversations()->updateExistingPivot($participation->conversation->id, [
                            'read_at' => Carbon::now(),
                        ]);
                    }

                    // Trigger updated_at refresh.
                    $participation->conversation->touch();
                }

                // Changer évolution des statuts
                activity()
                    ->performedOn($participation)
                    ->withProperties([
                        'attributes' => ['state' => 'Refusée'],
                        'old' => ['state' => $participation->state]
                    ])
                    ->event('updated')
                    ->log('updated');

                $participation->state = 'Refusée';
                $participation->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }
}

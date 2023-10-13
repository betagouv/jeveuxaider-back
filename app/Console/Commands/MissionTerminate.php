<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;
use App\Models\Message;

class MissionTerminate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mission:terminate {missionIds*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Passe le statut des missions en Terminée';

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
        $query = Mission::whereIn('id', $this->argument('missionIds'));

        if ($this->confirm('Passer le statut des missions en Terminée ? NB missions: ' . $query->count())) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $mission) {
                $mission->state = "Terminée";
                $mission->saveQuietly();

                $this->handleParticipations($mission);

                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function handleParticipations($mission)
    {
        // Notif OFF
        $mission->participations->whereIn('state', ['En attente de validation', 'En cours de traitement'])
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
    }
}

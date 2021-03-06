<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\MissionTemplate;
use Illuminate\Console\Command;

class MissionsCloseByTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:missions-close-by-template {templateIds*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ends all missions that uses template id {templateIds*}';

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
        $templateIds = $this->argument('templateIds');
        $queryMissions = Mission::whereIn('template_id', $templateIds)->available();

        foreach ($templateIds as $templateId) {
            $template = MissionTemplate::findOrFail($templateId);
            $templateCount = (clone $queryMissions)->where('template_id', $templateId)->count();
            $this->info("{$template->title} : {$templateCount}");
        }

        if ($this->confirm($queryMissions->count().' missions vont être mises à jour.')) {
            $missions = (clone $queryMissions)->get();

            // Without triggering observers, no notification sent.
            $queryMissions->update(['state' => 'Terminée']);

            foreach ($missions as $mission) {
                $queryPendingParticipations = $mission->participations()->where('state', 'En attente de validation');

                // Without triggering observers, no notification sent.
                $queryPendingParticipations->update(['state' => 'Annulée']);

                // With observers, notifications are sent.
                // foreach ($queryPendingParticipations->get() as $participation) {
                //     $participation->update(['state' => 'Annulée']);
                // }
            }
        }
    }
}

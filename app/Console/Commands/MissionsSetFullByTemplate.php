<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Models\Participation;
use Illuminate\Console\Command;

class MissionsSetFullByTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:missions-set-full-by-template {templateIds*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all places left for missions that uses template id {templateIds*}';

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
        $queryMissions = Mission::hasPlacesLeft()->available()->whereIn('template_id', $templateIds);

        foreach ($templateIds as $templateId) {
            $template = MissionTemplate::findOrFail($templateId);
            $templateCount = (clone $queryMissions)->where('template_id', $templateId)->count();
            $this->info("{$template->title} : {$templateCount}");
        }

        if ($this->confirm($queryMissions->count().' missions vont être mises à jour.')) {
            $missions = $queryMissions->get();
            foreach ($missions as $mission) {
                $nbParticipations = $mission->participations->whereIn('state', Participation::ACTIVE_STATUS)->count();

                if ($nbParticipations == 0) {
                    // ray('CLOSE MISSION : ' . $mission->id);
                    $mission->state = 'Terminée';
                } else {
                    // ray('REDUCE PARTICIPATION MAX : ' . $mission->id);
                    $mission->participations_max = $nbParticipations;
                    $mission->places_left = 0;
                }
                // Trigger scout reindex
                $mission->saveQuietly();
            }
        }
    }
}

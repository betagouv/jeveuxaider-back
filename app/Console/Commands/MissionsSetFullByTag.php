<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Participation;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class MissionsSetFullByTag extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:missions-set-full-by-tag {tagIds*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all places left for missions that uses template id {tagIds*}';

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
        $tagIds = $this->argument('tagIds');
        $query = Mission::hasPlacesLeft()->available()->whereHas('tags', function (Builder $query) use ($tagIds) {
            $query->whereIn('id', $tagIds)->where('field', 'mission_tags');
        });

        if ($this->confirm($query->count() . ' missions vont être mises à jour.')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $mission) {
                $mission->load('participations');
                if ($mission->participations()->count() == 0) {
                    $mission->state = 'Terminée';
                } else {
                    $nbParticipations = $mission->participations->whereIn('state', Participation::ACTIVE_STATUS)->count();
                    $mission->participations_max = $nbParticipations;
                    $mission->places_left = 0;
                }

                // Trigger scout reindex
                $mission->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }
}

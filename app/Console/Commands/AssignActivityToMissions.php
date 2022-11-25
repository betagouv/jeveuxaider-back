<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\Conversation;
use App\Models\Mission;
use App\Models\User;
use App\Services\ActivityClassifier;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class AssignActivityToMissions extends Command
{

    protected $signature = 'assign-activity-to-missions';
    protected $description = 'Assign activity to missions using ActivityClassifier';

    public function handle()
    {
        // $query = Mission::whereNull('activity_id');
        $query = Mission::whereNull('activity_id');

        if ($this->confirm($query->count() . ' missions will have an activity assigned. Continue ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $mission) {
                $this->assignActivity($mission);
                // To avoid too many requests.
                sleep(1);
                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function assignActivity(Mission $mission)
    {
        $payload = !empty($mission->template_id) ? [$mission->template->objectif, $mission->template->description] : [$mission->objectif, $mission->description];

        $response = ActivityClassifier::evaluate(implode(' ', $payload));

        if ($response['code'] !== 200) {
            ray($response);
            $this->error('Error code : ' . $response['code'] . ' | Mission id : ' . $mission->id);
            return;
        }

        $activityLabel = $response['content'][0][0];
        $activity = Activity::where('name', $activityLabel)->first();
        if (empty($activity)) {
            $this->info('Activité non existante : ' . $activityLabel);
            return;
        }

        $mission->activity_id = $activity->id;
        $mission->saveQuietly();
    }
}

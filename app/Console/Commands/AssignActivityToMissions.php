<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Services\ActivityClassifier;
use Illuminate\Console\Command;

class AssignActivityToMissions extends Command
{

    protected $signature = 'assign-activity-to-missions';
    protected $description = 'Assign activity to missions using ActivityClassifier';

    public function handle()
    {
        $this->handleMissions();
        $this->handleTemplates();
    }

    private function handleMissions()
    {
        $query = Mission::whereNull('activity_id')->whereNull('template_id');
        if ($this->confirm($query->count() . ' missions will have an activity assigned. Continue ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();
            foreach ($query->cursor() as $mission) {
                $this->assignActivity($mission, implode(' ', [$mission->objectif, $mission->description]));
                $bar->advance();
            }
            $bar->finish();
            $this->line(PHP_EOL);
        }
    }

    private function handleTemplates()
    {
        $query = MissionTemplate::whereNull('activity_id');
        if ($this->confirm($query->count() . ' templates will have an activity assigned. Continue ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();
            foreach ($query->cursor() as $template) {
                $this->assignActivity($template, implode(' ', [$template->objectif, $template->description]));
                $bar->advance();
            }
            $bar->finish();
            $this->line(PHP_EOL);
        }
    }

    private function assignActivity($model, $payload)
    {
        $response = ActivityClassifier::evaluate($payload);

        if ($response['code'] !== 200) {
            ray($response);
            $this->error('Error code : ' . $response['code'] . ' | ' . $model::class . ' :: ' . $model->id);
            $this->error($response['content']);
            return;
        }

        $activityLabel = $response['content'][0];
        $activity = Activity::where('name', $activityLabel)->first();
        if (empty($activity)) {
            $this->info('Activité non existante : ' . $activityLabel);
            return;
        }

        $model->activity_id = $activity->id;
        $model->saveQuietly();
    }
}

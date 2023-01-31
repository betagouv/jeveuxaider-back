<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Participation;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class MissionsRemoveHtmlFromAutonomyPrecisions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:missions-remove-html-from-autonomy-precisions {missionIds?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all HTML tags from the field autonomy_precisions';

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
        $missionIds = $this->argument('missionIds');
        $query = Mission::whereNotNull('autonomy_precisions')->where('autonomy_precisions', 'like', '<%');
        if (!empty($missionIds)) {
            $query->whereIn('id', $missionIds);
        }

        if ($this->confirm($query->count() . ' missions vont être mises à jour.')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $mission) {
                $output = $mission->autonomy_precisions;

                $output = strip_tags(str_replace('<', ' <', $output));
                $output = html_entity_decode($output);
                $output = str_replace('  ', ' ', $output);
                $output = trim($output);
                $mission->autonomy_precisions = $output;

                // Trigger scout reindex
                $mission->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }
}

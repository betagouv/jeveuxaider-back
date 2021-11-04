<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\MissionTemplate;
use Illuminate\Console\Command;

class TemplateMissionInvertTitleAndSubtitle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:template-mission-invert-title-and-subtitle {--exclude=*} {--include=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invert the content of the title and subtitle for all mission templates.';

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
        $excludeIds = $this->option('exclude');
        $includeIds = $this->option('include');

        $queryBuilder = MissionTemplate::whereNotIn('id', $excludeIds);
        if (!empty($includeIds)) {
            $queryBuilder->whereIn('id', $includeIds);
        }

        $this->info($queryBuilder->count() . ' templates will have their title and subtitle inverted');

        if ($this->confirm('Do you wish to continue?')) {
            $bar = $this->output->createProgressBar($queryBuilder->count());

            $bar->start();
            foreach ($queryBuilder->cursor() as $template) {
                $title = $template->title;
                $subtitle = $template->subtitle;

                $template->title = $subtitle;
                $template->subtitle = $title;
                $template->saveQuietly();
                $bar->advance();
            }
            $bar->finish();
        }
    }
}

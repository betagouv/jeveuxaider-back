<?php

namespace App\Console\Commands\MEP;

use App\Models\Media;
use App\Models\Mission;
use Illuminate\Console\Command;

class MediablesMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:mediables-missions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Rattachement des missions à des medias (remplace ancien champ thumbnail)";

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
        $query = Mission::withTrashed()->whereNotNull('thumbnail')->whereNull('template_id');
        $this->info("Ce script va rattacher les " . $query->count() . " missions à des medias");

        if ($this->confirm('Continuer ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $mission) {
                $this->addMediables($mission);
                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function addMediables($mission)
    {
        if ($mission->thumbnail) {
            $media = Media::where('collection_name', 'domaine__illustrations_mission')
                ->whereJsonContains('custom_properties->old_thumbnail', $mission->thumbnail)
                ->first();

            if ($media) {
                $mission->illustrations()->sync([$media->id => ['field' => 'mission_illustrations']]);
            }
        }
    }
}

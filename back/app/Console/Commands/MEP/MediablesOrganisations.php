<?php

namespace App\Console\Commands\MEP;

use App\Models\Media;
use App\Models\Structure;
use Illuminate\Console\Command;

class MediablesOrganisations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:mediables-organisations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Rattachement des organisations à des medias (remplace anciens champs image_1 & image_2)";

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
        $query = Structure::withTrashed()
            ->where(function ($query) {
                $query->whereNotNull('image_1')
                    ->orWhereNotNull('image_2');
            });
        $this->info("Ce script va rattacher les " . $query->count() . " organisations à des medias");

        if ($this->confirm('Continuer ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $organisation) {
                $this->addMediables($organisation);
                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function addMediables($organisation)
    {
        // Hack, certaines illustrations 1 & 2 sont identiques (~ 40)
        if ($organisation->image_1 === $organisation->image_2) {
            for ($i = 1; $i <= 5; $i++) {
                if (!strpos($organisation->image_1, "_" . $i)) {
                    $organisation->image_2 = substr_replace($organisation->image_1, $i, -1);
                    break;
                }
            }
        }

        $medias = Media::where('collection_name', 'domaine__illustrations_organisation')
            ->where(function ($query) use ($organisation) {
                $query->orWhereJsonContains('custom_properties->old_thumbnail', $organisation->image_1)
                    ->orWhereJsonContains('custom_properties->old_thumbnail', $organisation->image_2)
                ;
            })
            ->get();

        $syncArray = [];
        foreach ($medias as $media) {
            $syncArray[$media->id] = ['field' => 'organisation_illustrations'];
        }

        $organisation->illustrations()->sync($syncArray);
    }
}

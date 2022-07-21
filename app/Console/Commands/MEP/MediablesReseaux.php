<?php

namespace App\Console\Commands\MEP;

use App\Models\Media;
use App\Models\Reseau;
use Illuminate\Console\Command;

class MediablesReseaux extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:mediables-reseaux';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rattachement des reseaux à des medias (remplace anciens champs image_1 & image_2)';

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
        $query = Reseau::where(function ($query) {
            $query->whereNotNull('image_1')
                ->orWhereNotNull('image_2');
        });
        $this->info('Ce script va rattacher les '.$query->count().' reseaux à des medias');

        if ($this->confirm('Continuer ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $reseau) {
                $this->addMediables($reseau);
                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function addMediables($reseau)
    {
        // Hack, certaines illustrations 1 & 2 sont identiques (~ 40)
        if ($reseau->image_1 === $reseau->image_2) {
            for ($i = 1; $i <= 5; $i++) {
                if (! strpos($reseau->image_1, '_'.$i)) {
                    $reseau->image_2 = substr_replace($reseau->image_1, $i, -1);
                    break;
                }
            }
        }

        $medias = Media::where('collection_name', 'domaine__illustrations_organisation')
            ->where(function ($query) use ($reseau) {
                $query->orWhereJsonContains('custom_properties->old_thumbnail', $reseau->image_1)
                    ->orWhereJsonContains('custom_properties->old_thumbnail', $reseau->image_2);
            })
            ->get();

        $syncArray = [];
        foreach ($medias as $media) {
            $syncArray[$media->id] = ['field' => 'reseau_illustrations'];
        }

        $reseau->illustrations()->sync($syncArray);
    }
}

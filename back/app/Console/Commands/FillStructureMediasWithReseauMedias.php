<?php

namespace App\Console\Commands;

use App\Models\Structure;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FillStructureMediasWithReseauMedias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:fill-structure-medias-with-reseau-medias {id?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Duplicate reseau's medias and assign them to all their child structures. You can specify a list of ids to only run the script for these.";

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
        $reseauIds = $this->argument('id');
        $queryCount = Structure::whereNotNull('reseau_id')->where('is_reseau', false);
        if (!empty($reseauIds)) {
            $queryCount->whereIn('reseau_id', $reseauIds);
        }
        $this->info($queryCount->count() . ' structures will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $collection_name = 'structures';
            $queryReseaux = Structure::where('is_reseau', true);
            if (!empty($reseauIds)) {
                $queryReseaux->whereIn('id', $reseauIds);
            }
            $reseaux = $queryReseaux->get();

            foreach ($reseaux as $reseau) {
                $structures = Structure::where('reseau_id', $reseau->id)
                    ->where('is_reseau', false)
                    ->get();

                // Delete previous file
                foreach ($structures as $structure) {
                    $oldMedias = $structure->getMedia($collection_name);
                    foreach ($oldMedias as $oldMedia) {
                        $oldMedia->delete();
                    }
                }

                $reseau->media->each(function (Media $media) use ($structures, $collection_name) {
                    foreach ($structures as $structure) {
                        try {
                            // Add medias from reseau
                            assert($media instanceof Media);
                            $props = $media->toArray();
                            unset($props['id']);
                            $structure->addMediaFromUrl(Storage::disk('s3')->url($media->getPath()))
                                ->preservingOriginal()
                                ->withProperties($props)
                                ->toMediaCollection($collection_name);
                        } catch (Exception $e) {
                            //
                        }
                    }
                });
            }
        }
    }
}

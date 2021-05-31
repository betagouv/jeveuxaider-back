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
    protected $signature = 'cnut:fill-structure-medias-with-reseau-medias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Duplicate reseau's medias and assign them to all their child structures";

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
        $reseaux = Structure::where('is_reseau', true)->get();
        $query = Structure::whereNotNull('reseau_id')->where('is_reseau', false);
        $this->info($query->count() . ' structures will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $collection_name = 'structures';

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

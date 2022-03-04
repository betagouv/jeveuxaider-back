<?php

namespace App\Console\Commands\MEP;

use App\Models\Domaine;
use App\Models\Media;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media as ModelsMedia;
use Illuminate\Support\Str;

class MediaRefactoring extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:media-refactoring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Media refactoring";

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
        $query = ModelsMedia::query();
        $this->info($query->count() . ' medias will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $media) {
                $this->handleAttributePropertyConversion($media);
                $media->saveQuietly();
                $this->cleanOldMedia($media);
                $bar->advance();
            }

            $this->handleDomaineMedias();

            $bar->finish();
        }
    }

    private function handleAttributePropertyConversion($media)
    {
        if ($media->hasCustomProperty('field')) {
            $media->collection_name = Str::snake(class_basename($media->model)) . '__' . $media->getCustomProperty('field');
            $media->forgetCustomProperty('field');
        }

        switch ($media->model_type) {
            case 'App\Models\Profile':
                $media->collection_name = 'profile__avatar';
                break;
            case 'App\Models\Document':
                $media->collection_name = 'document__file';
                break;
            case 'App\Models\MissionTemplate':
                $media->collection_name = 'mission_template__photo';
                break;
        }

        return $media;
    }

    private function cleanOldMedia($media)
    {
        switch ($media->model_type) {
            case 'App\Models\Collectivity':
            case 'App\Models\Tag':
                $media->delete();
                break;

            case 'App\Models\MissionTemplate':
                if ($media->mime_type === 'image/svg+xml') {
                    $media->delete();
                }
                break;
        }
    }

    private function handleDomaineMedias()
    {
        $medias = Media::where('model_type', 'App\Models\Thematique')->with(['model'])->get();
        foreach ($medias as $media) {
            $thematique = $media->model;
            $thematique->load('domaine');
            $domaine = Domaine::where('name', $thematique->domaine->name)->first();
            if (Media::where('model_type', 'App\Models\Domaine')->where('model_id', $domaine->id)->where('collection_name', 'domaine__banner')->count()) {
                continue;
            }
            try {
                $mediaDomaine = $media->copy($domaine, 'domaines');
                $mediaDomaine->collection_name = 'domaine__banner';
                $mediaDomaine->saveQuietly();
            } catch (\Throwable $th) {
                $this->warn('Media # ' . $media->id . ' : File not found (' . $media->getFullUrl() . '). Skipped.');
            }
        }
    }
}

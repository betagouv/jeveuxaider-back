<?php

namespace App\Console\Commands\MEP;

use App\Models\Media;
use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media as ModelsMedia;

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

            $bar->finish();
        }
    }

    private function handleAttributePropertyConversion($media)
    {
        if ($media->hasCustomProperty('field')) {
            $media->setCustomProperty('attribute', $media->getCustomProperty('field'));
            $media->forgetCustomProperty('field');
        }

        switch ($media->model_type) {
            case 'App\Models\Profile':
                $media->setCustomProperty('attribute', 'avatar');
                break;
            case 'App\Models\Document':
                $media->setCustomProperty('attribute', 'file');
                break;
            case 'App\Models\Thematique':
                $media->setCustomProperty('attribute', 'image');
                break;
            case 'App\Models\MissionTemplate':
                $media->setCustomProperty('attribute', 'photo');
                break;
        }

        return $media;
    }

    private function cleanOldMedia($media)
    {
        if ($media->model_type === 'App\Models\Collectivity') {
            $media->delete();
        } elseif ($media->model_type === 'App\Models\Tag') {
            $media->delete();
        }

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
}

<?php

namespace App\Console\Commands\MEP;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media as ModelsMedia;

class MedialibraryUpgrade extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:medialibrary-upgrade';

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
                $this->handleGeneratedConversions($media);
                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function handleGeneratedConversions($media)
    {
        if (isset($media->custom_properties["generated_conversions"])) {
            $media->generated_conversions = $media->custom_properties["generated_conversions"];
            $media->forgetCustomProperty('generated_conversions');
            $media->saveQuietly();
        }
    }
}

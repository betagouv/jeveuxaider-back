<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media as ModelsMedia;

class MediaForceDisk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media-force-disk {disk : Name of the disk to use}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Media Force disk';

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
        $this->info($query->count().' medias will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $disk = $this->argument('disk');
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $media) {
                $this->forceDisk($media, $disk);
                $media->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }

    private function forceDisk($media, $disk)
    {
        $media->disk = $disk;
        $media->conversions_disk = $disk;

        return $media;
    }
}

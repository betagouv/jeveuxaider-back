<?php

namespace App\Console\Commands\MEP;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DeleteMediasThematique extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:delete-medias-thematique';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete medias thematique.";

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
        $query = Media::where('model_type', "App\Models\Thematique");
        $this->info($query->count() . ' medias will be deleted');

        if ($this->confirm('Do you wish to continue?')) {
            $query->delete();
        }
    }
}

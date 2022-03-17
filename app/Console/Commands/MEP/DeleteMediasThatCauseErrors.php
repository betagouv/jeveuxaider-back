<?php

namespace App\Console\Commands\MEP;

use App\Models\Media;
use Illuminate\Console\Command;

class DeleteMediasThatCauseErrors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:delete-medias-that-cause-errors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete medias that cause errors.";

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
        $ids = [
            6418,
            6417,
            4353,
            6423,
            6424,
            7593
        ];

        $this->info(count($ids) . ' medias will be deleted');

        if ($this->confirm('Do you wish to continue?')) {
            Media::whereIn('id', $ids)->delete();
        }
    }
}

<?php

namespace App\Console\Commands\MEP;

use App\Models\Media;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DeleteMediasThatCauseErrors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:delete-medias-that-cause-errors {--ids=*}';

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
            7593,
            11111,
            9231
        ];

        $optionIds = $this->option('ids');
        if (!empty($optionIds)) {
            if (! is_array($optionIds)) {
                $ids = explode(',', $optionIds);
            }
            elseif (count($optionIds) === 1 && Str::contains($optionIds[0], ',')) {
                $ids = explode(',', $optionIds[0]);
            }
            else {
                $ids = $optionIds;
            }
        }

        $this->info(count($ids) . ' medias will be deleted');

        if ($this->confirm('Do you wish to continue?')) {
            Media::whereIn('id', $ids)->delete();
        }
    }
}

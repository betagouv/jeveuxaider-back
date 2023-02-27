<?php

namespace App\Console\Commands;

use App\Models\Structure;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class GenerateStructuresResponseTime3Months extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'structures:response-time-3-months';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate structures response_time with 3 months timelaps';

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
     * @return int
     */
    public function handle()
    {

        $query = Structure::whereNotNull('response_time')
            ->whereHas('participations', function (Builder $query) {
                $query
                    ->where('participations.created_at', '>=',  Carbon::now()->subMonth(3)->toDateTimeString());
            })
            ->whereHas('participations', function (Builder $query) {
                $query
                    ->where('participations.created_at', '<',  Carbon::now()->subMonth(3)->toDateTimeString());
            });

        $this->info($query->count().' structures will be updated with new response_time');

        if ($this->confirm('Do you wish to continue ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $structure) {
                $structure->setResponseTime();
                $structure->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }
}

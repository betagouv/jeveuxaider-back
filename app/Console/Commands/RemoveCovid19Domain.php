<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Services\Airtable;
use Illuminate\Console\Command;

class RemoveCovid19Domain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove-domain-covid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove domain Covid19';

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
        $query = Mission::ofDomaine(5);

        if ($this->confirm($query->count().' missions will have tag Covid19')) {
            $query->chunk(50, function ($missions) {
                foreach ($missions as $mission) {
                    $mission->tags()->attach(724, ['field' => 'mission_tags']);
                }
                $this->comment("50 missions processed");
            });
        }
    }
}

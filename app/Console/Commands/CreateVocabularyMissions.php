<?php

namespace App\Console\Commands;

use App\Models\Vocabulary;
use Illuminate\Console\Command;

class CreateVocabularyMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vocabulary:create-missions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create vocabulary missions if not exists';

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
        $vocabulary = Vocabulary::where('name', 'Missions')->first();

        if($vocabulary){
            $this->info('Missions vocabulary already exists, abort.');
            return;
        }

        if ($this->confirm('Do you wish to continue?')) {
            Vocabulary::create([
                'name' => 'Missions'
            ]);
        }
    }
}

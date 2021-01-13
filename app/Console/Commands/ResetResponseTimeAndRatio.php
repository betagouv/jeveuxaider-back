<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\Structure;
use Illuminate\Console\Command;

class ResetResponseTimeAndRatio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:reset-response-time-and-ratio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset response time and ratio in conversation and structure';

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

        // RESET CONVERSATION RESPONSE TIME
        $globalQuery = Conversation::whereNotNull('response_time');
        $this->info($globalQuery->count() . ' conversations will be updated with response_time -> null');
        if ($this->confirm('Do you wish to continue?')) {
            $globalQuery->update(['response_time' => null]);
        }

        // RESET STRUCTURE RESPONSE TIME
        $globalQuery = Structure::whereNotNull('response_time');
        $this->info($globalQuery->count() . ' structures will be updated with response_time -> null');
        if ($this->confirm('Do you wish to continue?')) {
            $globalQuery->update(['response_time' => null]);
        }

        // RESET STRUCTURE RESPONSE TIME
        $globalQuery = Structure::whereNotNull('response_ratio');
        $this->info($globalQuery->count() . ' structures will be updated with response_ratio -> null');
        if ($this->confirm('Do you wish to continue?')) {
            $globalQuery->update(['response_ratio' => null]);
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Participation;
use Illuminate\Console\Command;

class FlushTrashParticipations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:flush-trash-participations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush participation in trash';

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
        $globalQuery = Participation::whereNotNull('deleted_at');

        $this->info($globalQuery->count() . ' participations are in trash');

        if ($this->confirm('Do you wish to continue?')) {
            $participations = $globalQuery->get();
            foreach ($participations as $participation) {
                $this->info("Participation #{$participation->id} processing ...");
                if ($participation->conversation) {
                    $participation->conversation->delete();
                    $this->warn("Conversation #{$participation->conversation->id} has been deleted");
                }
                $participation->deleteQuietly();
                $this->warn("Participation #{$participation->id} has been deleted");
            }
        }
    }
}

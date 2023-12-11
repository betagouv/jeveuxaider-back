<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UserInitLastInteractionAt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-init-last-interaction-at';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialise le champ last_interaction_at';

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
        if ($this->confirm('Initialiser le champ last_interaction_at avec le champ last_online_at ?')) {
            User::whereNull('last_interaction_at')
                ->whereNotNull('last_online_at')
                ->update(['last_interaction_at' => DB::raw('last_online_at')]);
        }
    }
}

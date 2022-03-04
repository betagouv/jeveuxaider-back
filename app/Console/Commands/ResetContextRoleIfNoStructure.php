<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Models\User;
use Illuminate\Console\Command;

class ResetContextRoleIfNoStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reset-context-role-if-no-structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set context_role to null if no more organisation attached';

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
        $usersQuery = User::where('context_role', 'responsable')->whereDoesntHave('structures');
        $this->info($usersQuery->count() . ' users context_role will be reset');
        if ($this->confirm('Do you wish to continue?')) {
            $usersQuery->update(['context_role' => null]);
        }
    }
}

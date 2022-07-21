<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserResetContextRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-reset-context-role {--user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset context role
                                {--user= : The user ID}';

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
        $options = $this->options();
        if (empty($options['user'])) {
            $this->error('Mandatory argument: --user');

            return;
        }

        $user = User::with(['profile'])->find($options['user'])->append(['roles']);

        if (! $user) {
            $this->error("This user {$options['user']} doesnt exists!");

            return;
        }

        $this->info("User {$user->profile->full_name} {$user->mail}");
        $this->info('Current context_role '.$user->context_role);
        $this->info('Current contextable_type '.$user->contextable_type);
        $this->info('Current contextable_id '.$user->contextable_id);

        if (count($user->roles)) {
            $this->info('New context_role '.serialize($user->roles[0]));
        } else {
            $this->info('New context_role volontaire');
        }

        if ($this->confirm('Continuer ?')) {
            $user->resetContextRole();
        }
    }
}

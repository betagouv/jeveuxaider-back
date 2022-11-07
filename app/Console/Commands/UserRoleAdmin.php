<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserRoleAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-role-admin {--action=} {--user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset context role
                                {--action= : add or remove}
                                {--origin= : The user ID}';

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
        if (empty($options['action'])) {
            $this->error('Mandatory argument: --action');

            return;
        }

        if (! in_array($options['action'], ['add', 'remove'])) {
            $this->error('action argument: add or remove');

            return;
        }

        $options = $this->options();
        if (empty($options['user'])) {
            $this->error('Mandatory argument: --user');

            return;
        }

        $user = User::with(['profile', 'roles'])->find($options['user']);

        if (! $user) {
            $this->error("This user {$options['user']} doesnt exists!");

            return;
        }

        $this->info("User {$user->profile->full_name} {$user->mail}");
        $this->info('Current context_role '.$user->context_role);
        $this->info('Current contextable_type '.$user->contextable_type);
        $this->info('Current contextable_id '.$user->contextable_id);

        if ($this->confirm('Continuer ?')) {
            if ($options['action'] == 'add') {
                $user->assignRole('admin');
                $user->resetContextRole();
                $this->info("User {$user->profile->full_name} {$user->mail} is now admin");
            }
            if ($options['action'] == 'remove') {
                $user->removeRole('admin');
                $user->resetContextRole();
                $this->info("User {$user->profile->full_name} {$user->mail} is no more admin");
            }
        }
    }
}

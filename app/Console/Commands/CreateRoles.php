<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class CreateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create roles
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
        if(Role::where('name', 'admin')->count() == 0) {
            if ($this->confirm('Roles admin, referents and responsables will be created')) {
                Role::create(['name' => 'admin']);
                Role::create(['name' => 'referent']);
                Role::create(['name' => 'responsable']);
            }
        }

        $usersAdmin = User::with('newRoles')->where('is_admin', true)->get();
        $usersAdmin->each(function ($user) {
            $user->assignRole('admin');
        });
        $this->info('Role admin assigned to ' . $usersAdmin->count() . ' users');

        $profilesResponsable = Profile::with('user.newRoles', 'structures')->whereHas('structures')->get();
        $profilesResponsable->each(function ($profile) {
            foreach ($profile->structures as $structure) {
                $profile->user->assignRole('responsable', $structure);
            }
        });
        $this->info('Role responsable assigned to ' . $profilesResponsable->count() . ' users');

    }
}

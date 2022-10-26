<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Models\Profile;
use App\Models\Region;
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
        // Fill regions and departments
        collect(config('taxonomies.regions.terms'))->each(function ($region_name) {
            Region::create(['name' => $region_name]);
        });
        collect(config('taxonomies.departments.terms'))->each(function ($department_name, $department_number) {
            $region = Region::where('name', config('taxonomies.department_region.terms')[$department_number])->get()->first();
            Department::create([
                'number' => $department_number,
                'name' => $department_name,
                'region_id' => $region ? $region->id : null,
            ]);
        });

        if (Role::where('name', 'admin')->count() == 0) {
            if (! $this->confirm('Roles will be created (admin, referent, responsable, referent_regional)')) {
                return;
            }
            Role::create(['name' => 'admin']);
            Role::create(['name' => 'responsable']);
            Role::create(['name' => 'referent']);
            Role::create(['name' => 'referent_regional']);
        }

        if (! $this->confirm('Do you want to migrate all user roles ?')) {
            return;
        }

        $usersAdmin = User::with('newRoles')->where('old_is_admin', true)->get();
        $usersAdmin->each(function ($user) {
            $user->assignRole('admin');
        });
        $this->info('Role admin assigned to '.$usersAdmin->count().' users');

        $profilesResponsable = Profile::with('user.newRoles', 'oldStructures')->whereHas('oldStructures')->get();
        $profilesResponsable->each(function ($profile) {
            foreach ($profile->oldStructures as $structure) {
                $profile->user->assignRole('responsable', $structure, $structure->pivot->fonction);
            }
        });
        $this->info('Role responsable assigned to '.$profilesResponsable->count().' users');

        $profilesReferent = Profile::with('user.newRoles')->whereNotNull('old_referent_department')->get();
        $profilesReferent->each(function ($profile) {
            $profile->user->assignRole('referent', Department::whereNumber($profile->old_referent_department)->get()->first());
        });
        $this->info('Role referent assigned to '.$profilesReferent->count().' users');

        $profilesReferentRegional = Profile::with('user.newRoles')->whereNotNull('old_referent_region')->get();
        $profilesReferentRegional->each(function ($profile) {
            $profile->user->assignRole('referent_regional', Region::whereName($profile->old_referent_region)->get()->first());
        });
        $this->info('Role referent régionnal assigned to '.$profilesReferentRegional->count().' users');
    }
}

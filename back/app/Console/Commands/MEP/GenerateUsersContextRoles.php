<?php

namespace App\Console\Commands\MEP;

use App\Models\Domaine;
use App\Models\MissionTemplate;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\Thematique;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateUsersContextRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:generate-users-context-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate user context roles";

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
        $responsablesQuery = User::where('context_role', 'responsable')->whereNull('contextable_type')->whereNull('contextable_id');

        $this->info($responsablesQuery->count() . ' responsables will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $bar = $this->output->createProgressBar($responsablesQuery->count());
            $bar->start();
            foreach ($responsablesQuery->cursor() as $user) {
                $structure = Structure::whereHas('members', function ($query) use ($user) {
                    return $query->where('profile_id', $user->profile->id);
                })->first();
                if ($structure) {
                    $user->contextable_type = 'structure';
                    $user->contextable_id = $structure->id;
                    $user->saveQuietly();
                    // $this->info('structure id ' . $structure->id . ' will be added to profile ' . $user->profile->id);
                } else {
                    $user->context_role = null;
                    $user->saveQuietly();
                    $this->warn('No structure for will be added to profile ' . $user->profile->id);
                }
                $bar->advance();
            }
            $bar->finish();
        }

        $membersQuery = Profile::whereHas('structures')->whereHas('user', function($query){
            $query->whereNull('context_role');
        });

        $this->info($membersQuery->count() . ' responsables will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $bar = $this->output->createProgressBar($membersQuery->count());
            $bar->start();
            foreach ($membersQuery->cursor() as $profile) {
                $structure = $profile->structures->first();
                if ($structure) {
                    $profile->user->context_role = 'responsable';
                    $profile->user->contextable_type = 'structure';
                    $profile->user->contextable_id = $structure->id;
                    $profile->user->saveQuietly();
                   // $this->info('structure id ' . $structure->id . ' will be added to user ' . $profile->user_id);
                } else {
                    $profile->user->context_role = null;
                    $profile->user->saveQuietly();
                    $this->warn('No structure for will be added to profile ' . $profile->user_id);
                }
                $bar->advance();
            }
            $bar->finish();
        }

        // Responsable territoire ?
        // Responsable reseau ?
    }
}

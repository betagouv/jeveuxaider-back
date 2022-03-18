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
        // Responsable
        $responsablesQuery = User::where('context_role', 'responsable')->whereNull('contextable_type')->whereNull('contextable_id');
        $this->info($responsablesQuery->count() . ' responsables will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            $this->updateResponsableWithNoContextableType($responsablesQuery);
        }

        // Members
        $membersQuery = Profile::whereHas('structures')->whereHas('user', function ($query) {
            $query->whereNull('context_role');
        });
        $this->info($membersQuery->count() . ' responsables will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            $this->updateResponsableWithNoContextRole($membersQuery);
        }

        // Responsable territoire
        $responsablesTerritoiresQuery = Profile::whereHas('territoires')->whereDoesntHave('structures')->whereHas('user', function ($query) {
            $query->whereNull('context_role');
        });
        $this->info($responsablesTerritoiresQuery->count() . ' responsables territoires will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            $this->updateResponsableTerritoiresWithNoContextRole($responsablesTerritoiresQuery);
        }

        // Tête de réseau
        $teteDeReseauQuery = Profile::whereNotNull('tete_de_reseau_id')->whereHas('user', function ($query) {
            $query->where('context_role', '!=', 'tete_de_reseau');
        });
        $this->info($teteDeReseauQuery->count() . ' têtes de réseau will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            $this->updateTetesDeReseau($teteDeReseauQuery);
        }

        // Referent departemental
        $referentDepQuery = User::whereHas('profile', function ($query) {
            $query->whereNotNull('referent_department');
        })->where(function ($query) {
            $query->where('context_role', '!=', 'referent')->orWhereNull('context_role');
        });
        $this->info($referentDepQuery->count() . ' referents department will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            $this->updateReferentDepWithNoContextRole($referentDepQuery);
        }

        // Referent regional
        $referentRegionalQuery = User::whereHas('profile', function ($query) {
            $query->whereNotNull('referent_region');
        })->where(function ($query) {
            $query->where('context_role', '!=', 'referent_regional')->orWhereNull('context_role');
        });
        $this->info($referentRegionalQuery->count() . ' referents regional will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            $this->updateReferentRegWithNoContextRole($referentRegionalQuery);
        }

        // Admin
        $adminsQuery = User::where('is_admin', true)->where('context_role', '!=', 'admin');
        $this->info($adminsQuery->count() . ' admins will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            $this->updateAdminWithNoContextRole($adminsQuery);
        }

        // Bénévoles
        $benevolesQuery = User::whereNull('context_role')
            ->whereHas('profile', function ($query) {
                $query
                    ->whereDoesntHave('structures')
                    ->whereDoesntHave('territoires')
                    ->whereNull('referent_department')
                    ->whereNull('referent_region')
                    ->whereNull('tete_de_reseau_id')
                    ->where('is_analyste', false);
            });
        $this->info($benevolesQuery->count() . ' benevoles will be updated');
        if ($this->confirm('Do you wish to continue?')) {
            $benevolesQuery->update(['context_role' => 'volontaire']);
        }
    }

    private function updateResponsableWithNoContextableType($query)
    {
        $bar = $this->output->createProgressBar($query->count());
        $bar->start();
        foreach ($query->cursor() as $user) {
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

    private function updateResponsableWithNoContextRole($query)
    {
        $bar = $this->output->createProgressBar($query->count());
        $bar->start();
        foreach ($query->cursor() as $profile) {
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

    private function updateResponsableTerritoiresWithNoContextRole($query)
    {
        $bar = $this->output->createProgressBar($query->count());
        $bar->start();
        foreach ($query->cursor() as $profile) {
            $territoire = $profile->territoires->first();
            if ($territoire) {
                $profile->user->context_role = 'responsable_territoire';
                $profile->user->contextable_type = 'territoire';
                $profile->user->contextable_id = $territoire->id;
                $profile->user->saveQuietly();
                $this->info('territoire id ' . $territoire->id . ' will be added to user ' . $profile->user_id);
            } else {
                $profile->user->context_role = null;
                $profile->user->saveQuietly();
                $this->warn('No territoire for will be added to profile ' . $profile->user_id);
            }
            $bar->advance();
        }
        $bar->finish();
    }

    private function updateReferentDepWithNoContextRole($query)
    {
        $bar = $this->output->createProgressBar($query->count());
        $bar->start();
        foreach ($query->cursor() as $user) {
            $user->context_role = 'referent';
            $user->contextable_type = null;
            $user->contextable_id = null;
            $user->saveQuietly();
            $this->info($user->email . ' has been switched to referent context_role');
            $bar->advance();
        }
        $bar->finish();
    }

    private function updateReferentRegWithNoContextRole($query)
    {
        $bar = $this->output->createProgressBar($query->count());
        $bar->start();
        foreach ($query->cursor() as $user) {
            $user->context_role = 'referent_regional';
            $user->contextable_type = null;
            $user->contextable_id = null;
            $user->saveQuietly();
            $this->info($user->email . ' has been switched to referent_regional context_role');
            $bar->advance();
        }
        $bar->finish();
    }

    private function updateTetesDeReseau($query)
    {
        $bar = $this->output->createProgressBar($query->count());
        $bar->start();
        foreach ($query->cursor() as $profile) {
            $reseau = $profile->reseau->first();
            $profile->user->context_role = 'tete_de_reseau';
            $profile->user->contextable_type = 'reseau';
            $profile->user->contextable_id = $reseau->id;
            $profile->user->saveQuietly();
            $this->info($profile->user->email . ' has been switched to tete_de_reseau context_role');
            $bar->advance();
        }
        $bar->finish();
    }
    private function updateAdminWithNoContextRole($query)
    {
        $bar = $this->output->createProgressBar($query->count());
        $bar->start();
        foreach ($query->cursor() as $user) {
            $user->context_role = 'admin';
            $user->contextable_type = null;
            $user->contextable_id = null;
            $user->saveQuietly();
            $this->info($user->email . ' has been switched to admin context_role');
            $bar->advance();
        }
        $bar->finish();
    }
}

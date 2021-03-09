<?php

namespace App\Console\Commands;

use App\Models\Invitation;
use App\Models\Mission;
use App\Models\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ConvertOrphanProfilesToInvitations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:convert-orphan-profiles-to-invitations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert orphan profiles to invitations';

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
        $query = Profile::whereNull('user_id');
        $this->info($query->count() . ' profiles will be converted to invitations');

        if ($this->confirm('Do you wish to continue?')) {
            $profiles = $query->get();
            foreach ($profiles as $profile) {
                $invitation = new Invitation;
                $invitation->user_id = 1;
                $invitation->token = Str::random(32);
                $invitation->email = $profile->email;

                $invitation->created_at = $profile->created_at;
                $invitation->updated_at = $profile->updated_at;
                $invitation->last_sent_at = $profile->created_at;

                if ($profile->referent_department) {
                    $invitation->role = 'referent_departemental';
                    $invitation->properties = ['referent_departemental'=>$profile->referent_department];
                // $this->info("Converting " . $profile->email. " / " . "Referent departemental du " . $profile->referent_department);
                } elseif ($profile->referent_region) {
                    $invitation->role = 'referent_regional';
                    $invitation->properties = ['referent_regional'=>$profile->referent_region];
                // $this->info("Converting " . $profile->email. " / " . "Referent régional du " . $profile->referent_region);
                } elseif ($profile->reseau_id) {
                    $invitation->role = 'superviseur';
                    $invitation->invitable_id = $profile->reseau_id;
                    $invitation->invitable_type = 'App\Models\Structure';
                // $this->info("Converting " . $profile->email. " / " . "Superviseur du " . $profile->reseau->name);
                } elseif ($profile->is_analyste) {
                    $invitation->role = 'datas_analyst';
                // $this->info("Converting " . $profile->email. " / " . "Analyste");
                } elseif ($structure = $profile->structures->first()) {
                    if ($structure) {
                        $invitation->invitable_id = $structure->id;
                        $invitation->invitable_type = 'App\Models\Structure';
                        $invitation->role = $structure->statut_juridique == 'Collectivité' ? 'responsable_collectivity' : 'responsable_organisation';
                        if (Mission::where('responsable_id', $profile->id)->count() > 0) {
                            $this->info("Converting " . $profile->email. " / " . "Responsable structure " . $structure->name . ' #' . $structure->id);
                            $structure->resetResponsable($profile);
                            $this->warn("Reseting responsable");
                        }
                    }
                } else {
                    $invitation->role = 'benevole';
                }

                $invitation->saveQuietly();

                $profile->delete();
            }
        }
    }
}

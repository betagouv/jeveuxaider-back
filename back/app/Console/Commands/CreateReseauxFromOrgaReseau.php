<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\Reseau;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Console\Command;

class CreateReseauxFromOrgaReseau extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:create-reseaux-from-orga-reseau';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Reseaux from orga with is_reseau';

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

        if ($this->confirm('Do you wish to continue?')) {
            // Clean field reseau_id
            Structure::withTrashed()->where('is_reseau', true)->update(['reseau_id' => null]);
            // Create Reseaux
            Structure::where('is_reseau', true)->each(function ($structure, $key) {
                $reseau = Reseau::updateOrCreate([
                    'name' => $structure->name
                ], [
                    'created_at' => $structure->created_at,
                    'updated_at' => $structure->updated_at
                ]);
                // Link antennes
                $this->info("Réseau " . $reseau->name . " créé ou updatée");
                $antennesIds = Structure::where('reseau_id', $structure->id)->pluck("id")->toArray();
                array_push($antennesIds, $structure->id);
                $this->info("Réseau " . $reseau->name . " a " . count($antennesIds) . " antennes");
                $reseau->structures()->syncWithoutDetaching($antennesIds);
                // Link profile to reseau
                Profile::where('reseau_id', $structure->id)->update(['tete_de_reseau_id' => $reseau->id]);
            });

            // Update context_role in users table
            User::where('context_role', 'superviseur')->update(['context_role' => 'tete_de_reseau']);
        }
    }
}
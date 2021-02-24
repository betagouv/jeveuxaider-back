<?php

namespace App\Console\Commands;

use App\Models\Collectivity;
use App\Models\Structure;
use Illuminate\Console\Command;

class CreateStructureForCollectivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:create-structure-for-collectivity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create structure for collectivity';

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
        $collectivities = Collectivity::where('type', 'commune')->doesntHave('structure')->has('profiles')->get();
        $this->info($collectivities->count() . ' collectivies will now have an organization');
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($collectivities as $collectivity) {
                $structure = Structure::create([
                    'user_id' => $collectivity->profiles[0]->user->id,
                    'name' => $collectivity->name,
                    'statut_juridique' => 'Collectivité',
                    'department' => $collectivity->department ?? substr($collectivity->zips[0], 0, 2),
                    'zip' => $collectivity->zips[0],
                    'state' => 'Validée'
                ]);
                $collectivity->structure_id = $structure->id;
                $collectivity->save();
            }
        }
    }
}

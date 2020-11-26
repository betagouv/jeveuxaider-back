<?php

namespace App\Console\Commands;

use App\Models\Collectivity;
use App\Models\Structure;
use Illuminate\Console\Command;

class CreateCollectivityForStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:create-collectivity-for-structure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create collectivity for structure';

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
        $structures = Structure::doesntHave('collectivity')
        ->where('statut_juridique', 'Collectivité')
        ->where(function ($query) {
            $query->where('name', 'like', 'Mairie%')
                ->orWhere('name', 'like', 'mairie%')
                ->orWhere('name', 'like', 'commune%')
                ->orWhere('name', 'like', 'Commune%')
                ->orWhere('name', 'like', 'Ville%')
                ->orWhere('name', 'like', 'ville%')
                ->orWhere('name', 'like', 'VILLE%')
                ->orWhere('name', 'like', 'COMMUNE%')
                ->orWhere('name', 'like', 'MAIRIE%');
        })
        ->get();
        
        $this->info($structures->count() . ' structures will now have a collectivity');
        if ($this->confirm('Do you wish to continue?')) {
            foreach ($structures as $structure) {
                $collectivity = Collectivity::create([
                    'name' => $structure->city ?? $structure->name,
                    'zips' => $structure->zip ? [$structure->zip] : [],
                    'structure_id' => $structure->id,
                    'published' => false,
                    'type' => 'commune',
                    'state' => 'waiting'
                ]);
                $collectivity->save();
            }
        }
    }
}

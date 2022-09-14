<?php

namespace App\Console\Commands\MEP;

use App\Models\Structure;
use Illuminate\Console\Command;

class FixStructureStatutJuridique extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mep:fix-structure-statut-juridique';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix structure statut juridique';

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
        $structuresPubliques = Structure::withTrashed()->where('statut_juridique', 'Structure publique');
        $this->info($structuresPubliques->count().' structures with Structure publique will be updated to Organisation publique');
        if ($this->confirm('Do you wish to continue?')) {
            $structuresPubliques->update([
                'statut_juridique' => 'Organisation publique',
            ]);
        }

        $structuresPrivees = Structure::withTrashed()->where('statut_juridique', 'Structure privée');
        $this->info($structuresPrivees->count().' structures with Structure privée will be updated to Organisation privée');
        if ($this->confirm('Do you wish to continue?')) {
            $structuresPrivees->update([
                'statut_juridique' => 'Organisation privée',
            ]);
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Structure;
use Illuminate\Console\Command;

class FillAssociationIdEtablissementApiFromRna extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rna:fill-association-id-etablissement-from-rna';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill Association ID from RNA';

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
     * @return int
     */
    public function handle()
    {
        $globalQuery = Structure::where('statut_juridique', 'Association')->whereNotNull('rna')->where('rna', '!=', 'N/A');

        $this->info($globalQuery->count() . ' structure will be updated with RNA Non Applicable');

        if ($this->confirm('Do you wish to continue?')) {
            // $globalQuery->update(['rna' => 'N/A']);
        }
    }
}

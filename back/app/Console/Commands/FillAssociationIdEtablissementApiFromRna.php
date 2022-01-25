<?php

namespace App\Console\Commands;

use App\Models\Structure;
use App\Services\ApiEngagement;
use Illuminate\Console\Command;

class FillAssociationIdEtablissementApiFromRna extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rna:fill-association-id-etablissement-from-rna {--limit=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill Association ID from RNA
                                {--limit= : Limit number to process}';

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
        $options = $this->options();

        $structures = Structure::where('statut_juridique', 'Association')
            ->whereNotNull('rna')
            ->where('rna', '!=', 'N/A')
            ->whereNull('api_id')
            ->take($options['limit'])
            ->get();

        $this->info($structures->count() . ' structure(s) will be updated with RNA Non Applicable');

        if ($this->confirm('Do you wish to continue?')) {
            foreach ($structures as $structure) {
                $apiEngagement = new ApiEngagement();
                $response = $apiEngagement->findAssociation([
                    'name' => $structure->rna,
                ]);

                $results = $response->json();

                if (count($results['data'])) {
                    $this->info('Processing structure ' . $structure->name . ' - RNA ' . $structure->rna);
                    $structure->api_id = $results['data'][0]['_id'];
                    $structure->saveQuietly();
                } else {
                    $structure->api_id = 'NOT_FOUND_API_ENGAGEMENT';
                    $structure->saveQuietly();
                    $this->info('Association not found in API: ' . $structure->name . ' - RNA ' . $structure->rna);
                }
            }
        }
    }
}

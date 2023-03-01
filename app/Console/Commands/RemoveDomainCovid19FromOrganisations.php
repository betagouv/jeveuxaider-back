<?php

namespace App\Console\Commands;

use App\Models\Structure;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class RemoveDomainCovid19FromOrganisations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove-domain-covid19-from-organisations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove domain Covid 19 from organisations';

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
        $query = Structure::whereHas('domaines', function (Builder $query) {
            $query->where('domaine_id', 5);
        });

        if ($this->confirm('Remove domain Covid-19 from ' . $query->count() . ' organisations ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();
            foreach ($query->cursor() as $organisation) {
                if ($organisation->domaines()->count() === 1) {
                    $organisation->domaines()->sync([1 => ['field' => 'structure_domaines']]);
                } else {
                    $organisation->domaines()->toggle([5 => ['field' => 'structure_domaines']]);
                }
                $bar->advance();
            }
            $bar->finish();
        }
    }
}

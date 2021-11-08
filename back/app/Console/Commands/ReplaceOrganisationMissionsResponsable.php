<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Profile;
use App\Models\Structure;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class ReplaceOrganisationMissionsResponsable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'replace:organisation-missions-responsable {organisation} {from} {to} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace organisation responsable in mission to another one
                                {organisation : id of organisation}
                                {from : id of responsable to replace}
                                {to : id of responsable to replace with}';

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
        $organisation_id = $this->argument('organisation');
        $organisation = Structure::find($organisation_id);

        if (!$organisation) {
            $this->error('This organisation does not exist');
            return;
        }

        $from_id = $this->argument('from');
        $responsableFrom = Profile::find($from_id);

        if (!$responsableFrom) {
            $this->error('This responsableFrom does not exist');
            return;
        }

        $to_id = $this->argument('to');
        $responsableTo = Profile::find($to_id);

        if (!$responsableTo) {
            $this->error('This responsableTo does not exist');
            return;
        }

        $query = Mission::where('responsable_id', $from_id)->where('structure_id', $organisation_id);

        $count = $query->count();

        if ($this->confirm("{$count} missions(s) responsable will be updated from {$responsableFrom->full_name} to {$responsableTo->full_name} in {$organisation->name}")) {
            $query->update(['responsable_id' => $to_id]);
            $this->info("{$count} missions(s) responsable  has been updated");
        }
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Services\ApiQPV;
use Illuminate\Console\Command;

class MissionsQPV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill-missions-field-qpv {--fromId=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill missions field QPV {--fromId= : Take missions > fromId }';

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
        $options = $this->options();
        $query = Mission::orderBy('id')->where('id', '>=', $options['fromId']);

        $this->comment($query->count() . " missions will be updated");

        $query->chunk(50, function ($missions) {
            foreach ($missions as $mission) {
                $isQPV = ApiQPV::isQPV($mission->address, $mission->zip, $mission->city);
                if($isQPV === true) {
                    $this->comment("IS QPV : #$mission->id  $mission->address $mission->zip, $mission->city");
                    $mission->is_qpv = true;
                    $mission->saveQuietly();
                } else {
                    $this->comment("no QPV : #$mission->id $mission->address $mission->zip, $mission->city");
                }
            }
            $this->comment("Processed 50 missions");
        });
        $this->info('coucou');
    }
}

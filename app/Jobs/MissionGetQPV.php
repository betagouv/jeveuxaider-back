<?php

namespace App\Jobs;

use App\Models\Mission;
use App\Services\Airtable;
use App\Services\ApiQPV;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MissionGetQPV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mission;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $isQPV = ApiQPV::isQPV($this->mission->address, $this->mission->zip, $this->mission->city);
        if($isQPV === true) {
            $this->mission->is_qpv = true;
            $this->mission->saveQuietly();
        }
    }
}

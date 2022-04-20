<?php

namespace App\Jobs;

use App\Models\Mission;
use App\Models\Structure;
use App\Services\Airtable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AirtableSyncObject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $object;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mission|Structure $object)
    {
        $this->object = $object;
        $this->onQueue('airtable');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $class_name = class_basename($this->object);
        if($class_name == 'Mission') {
            Airtable::syncMission($this->object);
        }
        else if($class_name == 'Structure') {
            Airtable::syncStructure($this->object);
        }
    }
}

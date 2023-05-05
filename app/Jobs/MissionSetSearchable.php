<?php

namespace App\Jobs;

use App\Models\Mission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

class MissionSetSearchable implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mission;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($missionId)
    {
        $this->mission = Mission::find($missionId);
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }
        if (!$this->mission) {
            return;
        }

        if ($this->mission->shouldBeSearchable()) {
            $this->mission->searchable();
        }
    }
}

<?php

namespace App\Jobs;

use App\Models\Mission;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Batchable;

class MissionWithSlotsSyncDatesJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
        if ($this->batch()?->cancelled()) {
            return;
        }
        if (!$this->mission) {
            return;
        }

        $firstSlot = Carbon::parse($this->mission->dates[0]['id']);
        $lastSlot = Carbon::parse($this->mission->dates[array_key_last($this->mission->dates)]['id']);

        if ($this->mission->start_date->notEqualTo($firstSlot)) {
            $this->mission->start_date = $firstSlot;
        }

        if ($this->mission->end_date->notEqualTo($lastSlot)) {
            $this->mission->end_date = $lastSlot;
        }

        $this->mission->save();

    }
}

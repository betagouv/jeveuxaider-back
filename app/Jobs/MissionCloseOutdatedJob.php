<?php

namespace App\Jobs;

use App\Models\Mission;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Activitylog\Facades\CauserResolver;

class MissionCloseOutdatedJob implements ShouldQueue
{
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
        if (!$this->mission) {
            return;
        }

        if ($this->mission->state === 'Validée' && ($this->mission->end_date < Carbon::now())) {
            CauserResolver::setCauser(null);
            $this->mission->state = 'Terminée';
            $this->mission->automatically_closed_at = Carbon::now();
            $this->mission->save();
        }
    }
}

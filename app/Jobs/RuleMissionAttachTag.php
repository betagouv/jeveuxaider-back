<?php

namespace App\Jobs;

use App\Models\Mission;
use App\Models\Rule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;

class RuleMissionAttachTag implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rule;
    protected $mission;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Rule $rule, Mission $mission)
    {
        $this->rule = $rule;
        $this->mission = $mission;
        $this->onQueue('rules');
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

        $this->mission->tags()->syncWithoutDetaching([$this->rule->action_value => ['field' => 'mission_tags']]);

        if ( $this->mission->shouldBeSearchable()) {
             $this->mission->searchable();
        }

        $this->rule->update([
            'triggers_count' => $this->rule->triggers_count + 1,
            'last_triggered_at' => Carbon::now()
        ]);
    }
}

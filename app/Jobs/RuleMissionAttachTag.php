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

class RuleMissionAttachTag implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rule;
    protected $mission;
    protected $tagId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Rule $rule, Mission $mission, $tagId)
    {
        $this->rule = $rule;
        $this->mission = $mission;
        $this->tagId = $tagId;
        $this->onQueue('rules');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->mission->tags()->syncWithoutDetaching([$this->tagId => ['field' => 'mission_tags']]);

        if ( $this->mission->shouldBeSearchable()) {
             $this->mission->searchable();
        }

        $this->rule->update([
            'triggers_count' => $this->rule->triggers_count + 1,
            'last_triggered_at' => Carbon::now()
        ]);
    }
}

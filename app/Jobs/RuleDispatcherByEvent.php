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

class RuleDispatcherByEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $event, $model)
    {
        $this->event = $event;
        $this->model = $model;
        $this->onQueue('rules');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Rule::active()->where('event', $this->event)->get()->each(function($rule) {
            if($rule->shouldExecuteOnModel($this->model)){
                $rule->executeOnModel($this->model);
            }
        });
    }
}

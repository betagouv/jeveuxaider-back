<?php

namespace App\Jobs;

use App\Models\Mission;
use App\Models\Structure;
use App\Models\User;
use App\Services\Airtable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AirtableDeleteObject implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $object;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mission|Structure|User $object)
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
        if ($class_name == 'Mission') {
            Airtable::deleteObject('mission', $this->object);
        } elseif ($class_name == 'Structure') {
            Airtable::deleteObject('structure', $this->object);
        } elseif ($class_name == 'User') {
            Airtable::deleteObject('user', $this->object);
        }
    }
}

<?php

namespace App\Jobs;

use App\Services\Sendinblue;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UsersSetHardBouncedAt implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $offset;
    protected $limit;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($offset, $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
        $this->onQueue('low-tasks');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Sendinblue::setHardBouncedAtUsers($this->offset, $this->limit);
    }
}

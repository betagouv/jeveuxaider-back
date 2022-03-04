<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class SchedulerDaemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:daemon {--sleep=60}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call the scheduler every minute.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while (true) {
            $this->line('<info>[' . Carbon::now()->format('Y-m-d H:i:s') . ']</info> Calling scheduler');

            $this->call('schedule:run');

            sleep($this->option('sleep'));
        }
    }
}

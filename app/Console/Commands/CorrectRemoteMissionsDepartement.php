<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class CorrectRemoteMissionsDepartement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mission:correct-remote-missions-department';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Correct the department of remote missions';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Mission::where('type', 'Mission à distance')
            ->whereHas('structure', function ($query) {
                $query->whereColumn('missions.department', '!=', 'structures.department');
            });

        $this->info($query->count() . ' missions will be updated');

        if ($this->confirm('Do you wish to continue ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $mission) {
                $mission->department = $mission->structure->department;
                $mission->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }
}

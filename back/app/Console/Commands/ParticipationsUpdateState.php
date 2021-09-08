<?php

namespace App\Console\Commands;

use App\Models\Participation;
use Illuminate\Console\Command;

class ParticipationsUpdateState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participations:update-state {id?*} {--old= : The state that will be replaced} {--new= : The new state}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace participations state with a new one.
                                {id : if present, only for these given mission ids}
                                {--old= : The old state to replace}
                                {--new= : The new state}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->options();
        if (empty($options['old']) || empty($options['new'])) {
            if (empty($options['old'])) {
                $this->error('Mandatory argument: --old');
            }
            if (empty($options['new'])) {
                $this->error('Mandatory argument: --new');
            }
            return;
        }

        $query = Participation::where('state', $options['old']);
        $ids = $this->argument('id');
        if (!empty($ids)) {
            $query->whereIn('mission_id', $ids);
        }
        $count = $query->count();

        if ($this->confirm($count . ' participations will be updated  --  [' . $options['old'] . '] => [' . $options['new'] . ']')) {
            $query->update(['state' => $options['new']]);
            $this->info($count . ' participations updated.');
        }
    }
}

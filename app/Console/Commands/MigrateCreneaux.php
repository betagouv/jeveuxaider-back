<?php

namespace App\Console\Commands;

use App\Models\Participation;
use Illuminate\Console\Command;

class MigrateCreneaux extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate-creneaux';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate creneaux';

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
        $query = Participation::whereNotNull('old_date');

        if (! $this->confirm($query->count() . ' participations avec créneaux à migrer ?')) {
            return;
        }

        $query->get()->each(function ($participation) {
            $participation->slots = [
                [
                    'date' => $participation->old_date,
                    'slots' => $participation->old_slots
                ]
            ];
            $participation->saveQuietly();
        });
    }
}

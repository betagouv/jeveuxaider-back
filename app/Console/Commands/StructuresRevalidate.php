<?php

namespace App\Console\Commands;

use App\Models\Structure;
use Illuminate\Console\Command;

class StructuresRevalidate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'structures:revalidate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revalidate previously validated organizations that were unsubscribed by script.';

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
     * @return int
     */
    public function handle()
    {
        $query = Structure::select('structures.id', 'structures.state')
            ->join('activity_log', 'structures.id', '=', 'activity_log.subject_id')
            ->whereBetween('activity_log.created_at', ['2024-03-14 00:00:00', '2024-03-15 23:59:59'])
            ->where('activity_log.description', 'updated')
            ->whereJsonContains('activity_log.properties->attributes->state', 'Désinscrite')
            ->whereJsonContains('activity_log.properties->old->state', 'Validée')
            ->whereNull('activity_log.causer_id')
            ->where('activity_log.subject_type', 'App\Models\Structure')
            ->where('structures.state', 'Désinscrite')
            ->orderBy('activity_log.id', 'DESC');

        if ($this->confirm($query->count() . ' organisations vont repasser en validées. Continer ?')) {
            $query->update([
                'state' => 'Validée'
            ]);
        }
    }
}

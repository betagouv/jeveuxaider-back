<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;

class MissionFillDateType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mission:fill-date-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto fill date_type column when null';

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
        if ($this->confirm('Ce script va automatiquement remplir la colonne date_type en fonction du la colonne commitment__time_period')) {
            $this->setDateTypeRecurring();
            $this->setDateTypePonctual();
        }
    }

    private function setDateTypeRecurring()
    {
        Mission::withTrashed()
            ->whereNotNull('commitment__time_period')
            ->whereNull('date_type')
            ->update(['date_type' => 'recurring']);
    }

    private function setDateTypePonctual()
    {
        Mission::withTrashed()
            ->whereNull('commitment__time_period')
            ->whereNull('date_type')
            ->update(['date_type' => 'ponctual']);
    }
}

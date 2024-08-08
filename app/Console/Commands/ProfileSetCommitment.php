<?php

namespace App\Console\Commands;

use App\Helpers\Utils;
use App\Models\Profile;
use Illuminate\Console\Command;

class ProfileSetCommitment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profile-set-commitment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set commitment based on old fields commitment__duration & commitment__time_period';

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
        $query = Profile::whereNotNull('commitment__duration')->whereNotNull('commitment__time_period')->whereNull('commitment');

        if ($this->confirm('Initialiser le champ commitment ? ' . $query->count())) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $profile) {
                $profile->timestamps = false;
                $profile->commitment = Utils::getCommitmentLabel(
                    $profile->commitment__duration,
                    $profile->commitment__time_period
                );
                $profile->saveQuietly();
                $bar->advance();
            }

            $bar->finish();
        }
    }
}

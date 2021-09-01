<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfilesMigrateCommitmentFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:profile-migrate-commitments-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old commitment fields to the new ones (frequence & frequence_granularite -> commitment__duration & commitment__time_period)';

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
        $queryBuilder = Profile::where(function ($query) {
            $query
                ->whereNotNull('frequence')
                ->orWhereNotNull('frequence_granularite');
        });
        $this->info($queryBuilder->count() . ' profiles will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $bar = $this->output->createProgressBar($queryBuilder->count());
            $bar->start();

            foreach ($queryBuilder->cursor() as $profile) {
                switch ($profile->frequence) {
                    case "1-2 heures":
                        $profile->commitment__duration = '1_hour';
                        break;
                    case "2-3 heures":
                        $profile->commitment__duration = '2_hours';
                        break;
                    case "4+ heures":
                        $profile->commitment__duration = 'half_day';
                        break;
                    case "1 jour":
                        $profile->commitment__duration = 'day';
                        break;
                    case "2 jours":
                        $profile->commitment__duration = '3_days';
                        break;
                    case "3+ jours":
                        $profile->commitment__duration = '5_days';
                        break;
                    default:
                        break;
                }

                switch ($profile->frequence_granularite) {
                    case "semaine":
                        $profile->commitment__time_period = 'week';
                        break;
                    case "mois":
                        $profile->commitment__time_period = 'month';
                        break;
                    case "annee":
                        $profile->commitment__time_period = 'year';
                        break;
                    default:
                        break;
                }

                $profile->setCommitmentTotal();
                $profile->saveQuietly();

                $bar->advance();
            }

            $bar->finish();
        }
    }
}
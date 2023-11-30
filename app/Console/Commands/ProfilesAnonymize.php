<?php

namespace App\Console\Commands;

use App\Jobs\ProfileFaker;
use App\Models\Profile;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class ProfilesAnonymize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profiles:anonymize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Anonymize all profiles";

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
        if (!in_array(config('app.env'), ['local', 'staging'])) {
            $this->error('This script can only be executed on testing environments');
            return;
        }

        $query = Profile::whereDoesntHave('user.roles', function (Builder $subQuery) {
            $subQuery->where('name', 'admin');
        })
        ->where('email', 'not like', '%@fake.test');

        $this->info($query->count() . ' profiles will be anonymized.');

        if ($this->confirm('Do you wish to continue ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $profile) {
                ProfileFaker::dispatchSync($profile);
                $bar->advance();
            }

            $bar->finish();
        }
    }
}

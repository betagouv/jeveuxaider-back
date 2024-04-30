<?php

namespace App\Console\Commands;

use App\Jobs\GeolocaliseProfileByZip;
use App\Models\Profile;
use Illuminate\Console\Command;

class CorrectCorsicaProfilesDepartment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:correct-corsica-profiles-department';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Correct profiles with departement code being 20. It should be 2A or 2B';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $profileQuery = Profile::where('department', 20);

        if ($this->confirm($profileQuery->count() . ' profiles will be updated. Do you wish to continue?')) {
            $profileQuery->cursor()->each(function (Profile $profile) {
                GeolocaliseProfileByZip::dispatch($profile);
            });
        }
    }
}

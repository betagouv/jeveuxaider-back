<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CorrectProfileDisponibilities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:correct-profile-disponibilities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Correct profile disponibilities from value to key';

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
        $profiles = Profile::whereJsonLength('disponibilities', '>=', 1)
            ->whereDate('created_at', '>', (new Carbon('2021-05-04 14:00:00')))
            ->get();

        $this->info($profiles->count(). ' profile(s) will be treated.');
        if ($this->confirm('Do you wish to continue?')) {
            $terms = config('taxonomies.profile_disponibilities.terms');
            foreach ($profiles as $profile) {
                $disponibilities = $profile->disponibilities;
                foreach ($profile->disponibilities as $key => $disponibility) {
                    if (empty($terms[$disponibility])) {
                        $machine_name = array_search($disponibility, $terms);
                        if ($machine_name) {
                            $disponibilities[] = $machine_name;
                        }
                        unset($disponibilities[$key]);
                    }
                }
                $profile->disponibilities = array_values(array_unique($disponibilities));
                $profile->saveQuietly();
            }
            $this->info('DONE. ' . $profiles->count() . ' profile(s) treated.');
        }
    }
}

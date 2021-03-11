<?php

namespace App\Console\Commands;

use App\Helpers\Utils;
use App\Models\Profile;
use Illuminate\Console\Command;

class AssignDepartmentToProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:assign-department-to-profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a department to profiles';

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
        $queryBuilder = Profile::whereNotNull('zip');
        $this->info($queryBuilder->count() . ' profiles will be updated');



        if ($this->confirm('Do you wish to continue?')) {
            $bar = $this->output->createProgressBar($queryBuilder->count());

            $bar->start();
            foreach ($queryBuilder->cursor() as $profile) {
                $profile->department = Utils::getDepartmentFromZip($profile->zip);
                $profile->saveQuietly();
                $bar->advance();
            }
            $bar->finish();
        }
    }
}

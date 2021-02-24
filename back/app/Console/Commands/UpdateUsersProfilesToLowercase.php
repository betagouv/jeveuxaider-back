<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateUsersProfilesToLowercase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:update-users-profiles-to-lowercase';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update emails to lowercase in users & profiles tables';

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
        $usersCount = User::whereRaw('email != lower(email)')
        ->whereRaw('lower(users.email) NOT IN (SELECT lower(email) As loweremail FROM users GROUP BY loweremail HAVING count(*)>1)')
        ->count();
        $this->info($usersCount . ' user emails will be converted to lowercase.');
        
        if ($this->confirm('Do you wish to continue?')) {
            DB::statement("UPDATE users SET email=lower(email) WHERE lower(users.email) NOT IN (SELECT lower(email) As loweremail FROM users GROUP BY loweremail HAVING count(*)>1) AND users.email != lower(users.email)");
            $this->info($usersCount . ' user emails converted!');
        }


        $profilesCount = Profile::whereRaw('email != lower(email)')
        ->whereRaw('lower(profiles.email) NOT IN (SELECT lower(email) As loweremail FROM profiles GROUP BY loweremail HAVING count(*)>1)')
        ->count();
        $this->info($profilesCount . ' profile emails will be converted to lowercase.');

        if ($this->confirm('Do you wish to continue?')) {
            DB::statement("UPDATE profiles SET email=lower(email) WHERE lower(profiles.email) NOT IN (SELECT lower(email) As loweremail FROM profiles GROUP BY loweremail HAVING count(*)>1) AND profiles.email != lower(profiles.email)");
            $this->info($profilesCount . ' profile emails converted!');
        }
    }
}

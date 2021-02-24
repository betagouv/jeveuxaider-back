<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Console\Command;

class RestoreProfileUserLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restore:profile-user-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore profile user link';

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
        $profiles = Profile::whereNull('user_id')->get();
        
        $profiles->each(function ($profile) {
            $user = User::where('email', $profile->email)->first();
            if ($user) {
                dump('Linking profile ' . $profile->id . ' to ' . $user->id . ' : ' . $profile->email);
                $user->profile()->save($profile);
            }
        });
    }
}

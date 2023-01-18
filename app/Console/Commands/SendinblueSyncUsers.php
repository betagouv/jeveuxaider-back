<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Sendinblue;
use Illuminate\Console\Command;

class SendinblueSyncUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendinblue:sync-users {--fromId=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users in Sendinblue {--fromId= : Take users > fromId }';

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
        $options = $this->options();
        $query = User::with(['profile', 'roles', 'structures', 'profile.participations', 'structures.missions', 'departmentsAsReferent', 'regionsAsReferent', 'profile.activities'])->orderBy('id')->where('id', '>=', $options['fromId']);

        if ($this->confirm($query->count().' users will be added or updated in Sendinnblue')) {
            $start = now();
            $time = $start->diffInSeconds(now());

            $this->comment("Processed in $time seconds");

            $query->chunk(50, function ($users) use ($start) {
                foreach ($users as $user) {
                    Sendinblue::sync($user);
                    $this->comment('Processed user '.$user->id);
                }
                $time = $start->diffInSeconds(now());
                $this->comment("Processed in $time seconds");
            });
        }
    }
}

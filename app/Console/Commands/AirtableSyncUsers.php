<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Airtable;
use Illuminate\Console\Command;

class AirtableSyncUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'airtable:sync-users {--fromId=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users referents in Airtable {--fromId= : Take users > fromId }';

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
        $query = User::role(['referent', 'referent_regional'])->with(['profile', 'roles', 'profile.tags'])->orderBy('id')->where('id', '>=', $options['fromId']);

        if ($this->confirm($query->count().' users will be added or updated in Airtable')) {
            $start = now();
            $time = $start->diffInSeconds(now());

            $this->comment("Processed in $time seconds");

            $query->chunk(50, function ($users) use ($start) {
                foreach ($users as $user) {
                    Airtable::syncUser($user);
                    $this->comment('Processed user '.$user->profile->id);
                }
                $time = $start->diffInSeconds(now());
                $this->comment("Processed in $time seconds");
            });
        }
    }
}

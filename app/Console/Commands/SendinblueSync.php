<?php

namespace App\Console\Commands;

use App\Services\Sendinblue;
use App\Models\User;
use Illuminate\Console\Command;

class SendinblueSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendinblue:sync {startId : Id to start from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync users in Sendinblue';

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
        $id = $this->argument('startId');
        $users = User::where('id', '>', $id)->count();
        $currentId = 0;
        if ($this->confirm($users . ' users will be added or updated in Sendinblue')) {
            User::where('id', '>', $id)->chunk(50, function ($users) {
                foreach ($users as $user) {
                    $response = Sendinblue::sync($user);
                    if (!$response->successful()) {
                        $this->info("Sendinblue sync failed for user $user->email with code : " . $response['code']);
                    }
                    $currentId = $user->id;
                }
                $this->info("Import has successfully finished until id " . $currentId);
            });
        }
    }
}

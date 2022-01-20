<?php

namespace App\Console\Commands;

use App\Services\Sendinblue;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class SendinblueSyncReferents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendinblue:sync-referents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync referents in Sendinblue';

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
        $query = User::whereHas('profile', function (Builder $query) {
            $query->where(function ($q) {
                $q->whereNotNull('referent_region')->orWhereNotNull('referent_department');
            });
        });
        if ($this->confirm($query->count() . ' users will be added or updated in Sendinblue')) {
            $query->chunk(50, function ($users) {
                foreach ($users as $user) {
                    $response = Sendinblue::sync($user);
                    if (!$response->successful()) {
                        $this->info("Sendinblue sync failed for user $user->email with code : " . $response['code']);
                    }
                }
            });
        }
    }
}

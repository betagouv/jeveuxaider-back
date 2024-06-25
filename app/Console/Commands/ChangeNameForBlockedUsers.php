<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

// TODO
// username, email
// profile.email
// ne plus mettre @anonymised ou @archived dans le code


class ChangeNameForBlockedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change-name-for-blocked-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean blocked user names';

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
        if ($this->confirm('Clean blocked user names ?')) {
            // Archived
            User::whereNotNull('users.archived_at')->update([
                'users.name' => DB::raw("CONCAT('archived-', id)"),
                'users.email' => DB::raw("CONCAT('archived-', id)")
            ]);
            Profile::whereHas('user', function (Builder $query) {
                $query->whereNotNull('users.archived_at');
            })->update([
                'profiles.email' => DB::raw("CONCAT('archived-', user_id)"),
            ]);

            // Anonymized
            User::whereNotNull('users.anonymous_at')->update([
                'users.name' => DB::raw("CONCAT('anonymized-', id)"),
                'users.email' => DB::raw("CONCAT('anonymized-', id)")
            ]);
            Profile::whereHas('user', function (Builder $query) {
                $query->whereNotNull('users.anonymous_at');
            })->update([
                'profiles.email' => DB::raw("CONCAT('anonymized-', user_id)"),
            ]);
        }
    }
}

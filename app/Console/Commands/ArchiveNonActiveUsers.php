<?php

namespace App\Console\Commands;

use App\Jobs\ArchiveAndClearUserDatas;
use App\Jobs\CloseOrTransferResponsableMissions;
use App\Jobs\SendinblueDeleteUser;
use App\Jobs\UserCancelWaitingParticipations;
use App\Models\User;
use Illuminate\Console\Command;

class ArchiveNonActiveUsers extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'archive-non-active-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive users that have not been active for three years or more';

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
        $query = User::shouldBeArchived()->orderBy('id');
        $query->cursor()->each(function (User $user) {
            $user->loadMissing('roles');
            UserCancelWaitingParticipations::dispatch($user, 'user_archived');
            SendinblueDeleteUser::dispatch($user->email, "user_archived");
            CloseOrTransferResponsableMissions::dispatchIf($user->hasRole('responsable'), $user);
            ArchiveAndClearUserDatas::dispatch($user);
        });
    }
}

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
    protected $signature = 'archive-non-active-users {--debug} {--maxId=}';

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
        $options = $this->options();
        if (empty($options['maxId'])) {
            $this->error('Mandatory option: --maxId');
            return;
        }

        $query = User::shouldBeArchived()
            ->orderBy('id')
            ->where('id', '<=', $options['maxId']);

        if ($this->confirm($query->count() . ' utilisateurs vont être archivés. Continuer ?')) {
            $start = now();
            $executionTime = 0;

            foreach($query->cursor() as $user) {
                $user->loadMissing('roles');

                if ($options['debug']) {
                    $this->comment($user->id . " - " . $user->email);
                }

                UserCancelWaitingParticipations::dispatch($user, 'user_archived');
                SendinblueDeleteUser::dispatch($user);
                CloseOrTransferResponsableMissions::dispatchIf($user->hasRole('responsable'), $user);
                ArchiveAndClearUserDatas::dispatch($user);

                $time = $start->diffInSeconds(now());
                if ($executionTime !== $time && ($time - $executionTime) % 5 === 0) {
                    $this->comment("Processing... ($time seconds)");
                    $executionTime = $time;
                }
            }
        }

        // @todo: script auto avec kernel

        // $query = User::shouldBeArchived()->orderBy('id');
        // $query->cursor()->each(function (User $user) {
        //     $user->loadMissing('roles');
        //     UserCancelWaitingParticipations::dispatch($user, 'user_archived');
        //     SendinblueDeleteUser::dispatch($user);
        //     CloseOrTransferResponsableMissions::dispatchIf($user->hasRole('responsable'), $user);
        //     ArchiveAndClearUserDatas::dispatch($user);
        // });
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\BenevoleWillBeArchived;
use App\Notifications\ResponsableWillBeArchived;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationsUserWillBeArchived extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:user-will-be-archived';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to users that will be archived in 7 days';

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
        // 7 jours avant le passage du script qui archive les utilisateurs.
        $date = Carbon::now()->subYears(3)->addDays(7);
        $query = User::where('last_interaction_at', "<", $date)
            ->canBeEmailed();

        if ($this->confirm($query->count() . ' users will get a notification')) {
            $start = now();
            $executionTime = 0;
            $query->chunk(200, function ($users) use ($start, &$executionTime) {
                foreach ($users as $user) {
                    $user->loadMissing('roles');
                    if ($user->hasRole('responsable')) {
                        $user->loadMissing('structures');
                        $structure = $user->structures->first();
                        if ($structure) {
                            Notification::send($user, new ResponsableWillBeArchived($structure));
                        }
                    } else {
                        Notification::send($user, new BenevoleWillBeArchived());
                    }
                }

                $time = $start->diffInSeconds(now());
                if ($executionTime !== $time && ($time - $executionTime) % 5 === 0) {
                    $this->comment("Processing... ($time seconds)");
                    $executionTime = $time;
                }
            });
        }
    }
}

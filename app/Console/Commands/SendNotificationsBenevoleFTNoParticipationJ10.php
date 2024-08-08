<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Notifications\BenevoleFTNoParticipationJ10;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationsBenevoleFTNoParticipationJ10 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:benevole-ft-no-participation-j10';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications 10 days after to FT RSA Volunteer if they have no participation.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Profile::where('ft', true)
            ->whereBetween('ft_updated_at', [
                Carbon::now()->subDays(10)->startOfDay(),
                Carbon::now()->subDays(10)->endOfDay(),
            ])
            ->whereDoesntHave('participations');

        foreach ($query->get() as $profile) {
            Notification::send($profile, new BenevoleFTNoParticipationJ10($profile));
        }
    }
}

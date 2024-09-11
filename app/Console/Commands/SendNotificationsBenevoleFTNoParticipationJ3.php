<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Notifications\BenevoleFTNoParticipationJ3;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationsBenevoleFTNoParticipationJ3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:benevole-ft-no-participation-j3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications 3 days after to FT RSA Volunteer if they have no participation.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ray('SendNotificationsBenevoleFTNoParticipationJ3');

        $query = Profile::where('ft', true)
            ->whereBetween('ft_updated_at', [
                Carbon::now()->subDays(3)->startOfDay(),
                Carbon::now()->subDays(3)->endOfDay(),
            ])
            ->whereDoesntHave('participations');

        foreach ($query->get() as $profile) {
            ray($profile->email);
            Notification::send($profile, new BenevoleFTNoParticipationJ3($profile));
        }
    }
}

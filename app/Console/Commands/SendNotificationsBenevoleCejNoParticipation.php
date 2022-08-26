<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Notifications\BenevoleCejNoParticipation;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationsBenevoleCejNoParticipation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:benevole-cej-no-participation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to CEJ Volunteer if they have no participation 10 days after their subscription.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Profile::where('cej', true)
            ->whereBetween('created_at', [
                Carbon::now()->subDays(10)->startOfDay(),
                Carbon::now()->subDays(10)->endOfDay(),
            ])
            ->whereDoesntHave('participations');

        foreach ($query->get() as $profile) {
            Notification::send($profile, new BenevoleCejNoParticipation($profile));
        }
    }
}

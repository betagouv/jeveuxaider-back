<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Notifications\BenevoleCejSixMonthsAfter;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationsBenevoleCejSixMonthsAfter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:benevole-cej-six-months-after';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to CEJ Volunteer 6 months after.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Profile::where('cej', true)
            ->whereBetween('cej_updated_at', [
                Carbon::now()->subMonths(6)->startOfDay(),
                Carbon::now()->subMonths(6)->endOfDay(),
            ]);

        foreach ($query->get() as $profile) {
            Notification::send($profile, new BenevoleCejSixMonthsAfter($profile));
        }
    }
}

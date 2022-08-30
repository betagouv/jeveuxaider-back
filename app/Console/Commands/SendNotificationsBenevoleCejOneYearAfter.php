<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Notifications\BenevoleCejOneYearAfter;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationsBenevoleCejOneYearAfter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:benevole-cej-one-year-after';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to CEJ Volunteer 1 year after.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Profile::where('cej', true)
            ->whereBetween('created_at', [
                Carbon::now()->subYear()->startOfDay(),
                Carbon::now()->subYear()->endOfDay(),
            ])
            ->whereDoesntHave('participations');

        foreach ($query->get() as $profile) {
            Notification::send($profile, new BenevoleCejOneYearAfter($profile));
        }
    }
}

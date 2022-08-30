<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Notifications\RegisterUserVolontaireCej;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendNotificationsRegisterUserVolontaireCej extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:register-benevole-cej';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to CEJ Volunteer after 3 days';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Profile::where('cej', true)
            ->whereBetween('cej_updated_at', [
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay(),
            ]);

        foreach ($query->get() as $profile) {
            Notification::send($profile, new RegisterUserVolontaireCej($profile));
        }
    }
}

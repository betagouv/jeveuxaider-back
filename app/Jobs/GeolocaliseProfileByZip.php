<?php

namespace App\Jobs;

use App\Helpers\Utils;
use App\Models\Profile;
use App\Services\ApiAdresse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeolocaliseProfileByZip implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Profile $profile)
    {
        $this->onQueue('low-tasks');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $q = $this->profile->zip . ' ' . $this->profile->city;
        $place = ApiAdresse::search(['q' => $q, 'type' => 'municipality', 'limit' => 1]);

        if (!empty($place)) {
            $this->profile
                ->update([
                    'latitude' => $place['geometry']['coordinates'][1],
                    'longitude' => $place['geometry']['coordinates'][0],
                    'city' => $place['properties']['city'],
                    'department' => Utils::getDepartmentFromCitycode($place['properties']['citycode']),
                ]);
        }
    }
}

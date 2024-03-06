<?php

namespace App\Jobs;

use App\Models\Profile;
use App\Services\ApiAdresse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GeolocaliseProfilesByZip implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $zip)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $place = ApiAdresse::search(['q' => $this->zip, 'type' => 'municipality', 'limit' => 1]);

        if (!empty($place)) {
            Profile::where('zip', $this->zip)
                ->update([
                    'latitude' => $place['geometry']['coordinates'][1],
                    'longitude' => $place['geometry']['coordinates'][0],
                    'city' => $place['properties']['city'],
                ]);
        } else {
            Profile::where('zip', $this->zip)->update([
                'latitude' => 0,
                'longitude' => 0,
                'city' => null,
            ]);
        }
    }
}

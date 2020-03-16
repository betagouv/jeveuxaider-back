<?php

namespace App\Console\Commands;

use App\Models\Young;
use Illuminate\Console\Command;
use Spatie\Geocoder\Facades\Geocoder;

class GeocodeYoungs extends Command
{
    protected $signature = 'geocode:youngs';

    protected $description = 'Geocode Youngs based on their adresses';

    public $failures = [];

    public function handle()
    {
        $this->output->title('Starting geocoding');

        $youngs = Young::whereNull('regular_latitude')
            ->whereNull('regular_longitude')
            ->get();

        $this->output->text(count($youngs) . ' jeunes à géocoder');

        foreach ($youngs as $young) {
            $geocode = Geocoder::getCoordinatesForAddress($young->regular_city);
            if ($geocode['lat'] && $geocode['lng']) {
                $young->regular_latitude = $geocode['lat'];
                $young->regular_longitude = $geocode['lng'];
                $young->save();
            } else {
                $this->failures[] = $young->regular_city;
                $this->output->text($young->regular_city . ' n\'a pas pu être géocodé !');
            }
        }

        $this->output->title(count($this->failures) . ' Fails');

        $this->output->success('Ending geocoding');
    }
}

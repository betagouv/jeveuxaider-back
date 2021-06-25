<?php

namespace App\Console\Commands;

use App\Models\Collectivity;
use App\Models\Territoire;
use Illuminate\Console\Command;

class ConvertCollectivityToTerritoire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:convert-collectivity-to-territoire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert collectivities to territories';

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
     * @return int
     */
    public function handle()
    {
        $collectivities = Collectivity::get();
        if ($this->confirm($collectivities->count() . ' villes et départements vont être crées à partir des collectivités')) {
            $collectivities->each(function($collectivity) {
                $territoire = Territoire::create([
                    'name' => $collectivity->name,
                    'suffix_title' => $collectivity->type == 'commune' ? 'à ' . $collectivity->name : 'dans le département ' . $collectivity->name,
                    'department' => $collectivity->department,
                    'zips' => $collectivity->zips,
                    'description' => $collectivity->description,
                    'is_published' => $collectivity->published,
                    'type' => $collectivity->type == 'commune' ? 'city' : $collectivity->type,
                    'state' => $collectivity->state
                ]);
                $mediaBanner = $collectivity->getFirstMedia('collectivities', ['field' => 'banner']);
                if($mediaBanner) {
                    // $mediaBanner->copy($territoire, 'territoires');
                }
                $mediaLogo = $collectivity->getFirstMedia('collectivities', ['field' => 'logo']);
                if($mediaLogo) {
                    // $mediaLogo->copy($territoire, 'territoires');
                }
                if($collectivity->structure) {
                    $collectivity->structure->responsables()->each(function ($responsable) use ($territoire) {
                        $territoire->addResponsable($responsable);
                    });
                }
            });
        }
    }
}

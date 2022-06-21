<?php

namespace App\Console\Commands;

use App\Models\Mission;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class ApiEngagementExportMissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apieng:export-missions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export missions in Api engagement';

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
     * @return mixed
     */
    public function handle()
    {
        $structuresNotInApi = [25, 7383, 5577]; // Bénénovat
        $missions = Mission::with(['domaine', 'template', 'template.domaine', 'template.photo', 'structure', 'illustrations'])->whereHas('structure', function (Builder $query) use ($structuresNotInApi) {
            $query->where('state', 'Validée')
                  ->whereNotIn('id', $structuresNotInApi);
        })->where('state', 'Validée')->where('places_left', '>', 0)->get();

        $output = View::make('flux-api-engagement')->with(compact('missions'))->render();

        Storage::disk('s3')->put('public/flux-api-engagement.xml', $output, 'public');
    }
}
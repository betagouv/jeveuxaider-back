<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\MissionTemplate;
use App\Models\Participation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FillMissionTemplatePhoto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:fill-mission-template-photo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill missionTemplate photo from static folder';

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
        $query = MissionTemplate::count();
        $this->info($query  . ' mission templates will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            MissionTemplate::get()->each(function ($missionTemplate, $key) {
                $pathImage = public_path('/images/templates/' . $missionTemplate->id . '@2x.jpg');
                if(File::exists($pathImage)){
                    $name = Str::random(15);
                    $missionTemplate
                        ->addMedia($pathImage)
                        ->usingName($name)
                        ->usingFileName($name . '.jpg')
                        ->withCustomProperties(['field' => 'photo'])
                        ->toMediaCollection('templates');
                } else {
                    $this->info('no image file in static folder for missionTemplate id ' . $missionTemplate->id);
                }
            });
        }
    }
}

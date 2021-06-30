<?php

namespace App\Console\Commands;

use App\Models\Collectivity;
use App\Models\Territoire;
use Illuminate\Console\Command;

class UpdateTerritoireDatetime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:update-territoire-datetime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update territoire datetime with value from collectivities';

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
        $territoires = Territoire::get();

        if ($this->confirm($territoires->count() . ' territoires vont être mis à jour')) {
            $territoires->each(function($territoire) {
                $collectivity = Collectivity::where('name', $territoire->name)->first();
                if($collectivity) {
                    $territoire->created_at = $collectivity->created_at;
                    $territoire->updated_at = $collectivity->updated_at;
                    $territoire->save();
                }
            });
        }
    }
}

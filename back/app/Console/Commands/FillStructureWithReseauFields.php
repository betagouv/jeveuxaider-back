<?php

namespace App\Console\Commands;

use App\Models\Structure;
use Illuminate\Console\Command;

class FillStructureWithReseauFields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cnut:fill-structure-with-reseau-fields {id?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Fill structure with their reseau's fields. You can specify a list of ids to only run the script for these.";

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
        $reseauIds = $this->argument('id');
        $queryCount = Structure::whereNotNull('reseau_id')->where('is_reseau', false);
        if (!empty($reseauIds)) {
            $queryCount->whereIn('reseau_id', $reseauIds);
        }
        $this->info($queryCount->count() . ' structures will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $queryReseaux = Structure::where('is_reseau', true);
            if (!empty($reseauIds)) {
                $queryReseaux->whereIn('id', $reseauIds);
            }
            $reseaux = $queryReseaux->get();

            foreach ($reseaux as $reseau) {
                $array = [
                    'image_1' => $reseau->image_1,
                    'image_2' => $reseau->image_2,
                    'color' => $reseau->color,
                ];

                Structure::where('reseau_id', $reseau->id)
                    ->where('is_reseau', false)
                    ->update($array);

                // Description - only if empty.
                Structure::where('reseau_id', $reseau->id)
                    ->where('is_reseau', false)
                    ->where(function ($query) {
                        $query
                            ->where('description', '')
                            ->orWhereNull('description');
                    })
                    ->update(['description' => $reseau->description]);

                // Donation - only if empty.
                Structure::where('reseau_id', $reseau->id)
                    ->where('is_reseau', false)
                    ->where(function ($query) {
                        $query
                            ->where('donation', '')
                            ->orWhereNull('donation');
                    })
                    ->update(['donation' => $reseau->donation]);
            }
        }
    }
}

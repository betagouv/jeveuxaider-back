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
    protected $signature = 'cnut:fill-structure-with-reseau-fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Fill structure with their reseau's fields";

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
        $reseaux = Structure::where('is_reseau', true)->get();
        $query = Structure::whereNotNull('reseau_id')->where('is_reseau', false);
        $this->info($query->count() . ' structures will be updated');

        if ($this->confirm('Do you wish to continue?')) {
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

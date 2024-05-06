<?php

namespace App\Jobs;

use App\Models\Structure;
use App\Models\Territoire;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTerritoireFromStructure implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Structure $structure)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $name = preg_replace("/(^Mairie (des|du|de|d')*)/mi", '', $this->structure->name);

        $territoire = Territoire::create([
            'structure_id' =>  $this->structure->id,
            'name' => $name,
            'suffix_title' => 'à ' . $name,
            'zips' =>  $this->structure->zip ? [$this->structure->zip] : [],
            'department' =>  $this->structure->department,
            'is_published' => false,
            'type' => 'city',
            'state' => 'waiting',
        ]);

        $responsable = $this->structure->members->first();

        if ($responsable) {
            $territoire->addResponsable($responsable);
        }
    }
}

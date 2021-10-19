<?php

namespace App\Jobs;

use App\Filters\FiltersStructureCeu;
use App\Filters\FiltersStructureLieu;
use App\Filters\FiltersStructureSearch;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\FastExcel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\File;

class ProcessExportStructures implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $role;
    private $filePath;
    private $fileName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, $role, $filePath, $fileName)
    {
        $this->user = $user;
        $this->role = $role;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $query = QueryBuilder::for(Structure::role($this->role)->with('user'))
            ->allowedFilters([
                'department',
                'state',
                'statut_juridique',
                AllowedFilter::custom('ceu', new FiltersStructureCeu),
                AllowedFilter::custom('lieu', new FiltersStructureLieu),
                AllowedFilter::custom('search', new FiltersStructureSearch),
            ])
            ->defaultSort('-created_at')
            ->get();

        $exportFilePath = (new FastExcel($query))
        ->export($this->fileName, function ($structure) {
            return [
                'id' => $structure->id,
                'name' => $structure->name,
                'state' => $structure->state,
                'response_ratio' => $structure->response_ratio,
                'response_time' => $structure->response_time,
                'statut_juridique' => $structure->statut_juridique,
                'association_types' => $structure->association_types,
                'structure_publique_type' => $structure->structure_publique_type,
                'structure_publique_etat_type' => $structure->structure_publique_etat_type,
                'structure_privee_type' => $structure->structure_privee_type,
                'siret' => $structure->siret,
                'description' => $structure->description,
                'full_address' => $structure->full_address,
                'address' => $structure->address,
                'department' => $structure->department,
                'latitude' => $structure->latitude,
                'longitude' => $structure->longitude,
                'zip' => $structure->zip,
                'city' => $structure->city,
                'country' => $structure->country,
                'website' => $structure->website,
                'instagram' => $structure->instagram,
                'facebook' => $structure->facebook,
                'twitter' => $structure->twitter,
                'created_at' => $structure->created_at,
                'updated_at' => $structure->updated_at,
                'user_id' => $structure->user_id,
                'user_email' => $structure->user->email,
            ];
        });

        Storage::disk('s3')->put($this->filePath, file_get_contents($exportFilePath));

        File::delete($exportFilePath);
    }
}

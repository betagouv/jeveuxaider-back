<?php

namespace App\Jobs;

use App\Filters\FiltersDisponibility;
use App\Filters\FiltersMatchMission;
use App\Filters\FiltersProfileMinParticipations;
use App\Filters\FiltersProfileRole;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileSkill;
use App\Filters\FiltersProfileTag;
use App\Filters\FiltersProfileZips;
use App\Models\Profile;
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

class ProcessExportProfiles implements ShouldQueue
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
        $query = QueryBuilder::for(Profile::role($this->role))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('zips', new FiltersProfileZips),
                AllowedFilter::custom('role', new FiltersProfileRole),
                AllowedFilter::custom('domaines', new FiltersProfileTag),
                AllowedFilter::custom('disponibilities', new FiltersDisponibility),
                AllowedFilter::custom('skills', new FiltersProfileSkill),
                // AllowedFilter::custom('match_mission', new FiltersMatchMission),
                AllowedFilter::exact('is_visible'),
                AllowedFilter::custom('min_participations', new FiltersProfileMinParticipations),
                AllowedFilter::exact('referent_department'),
                'referent_region'
            )
            ->defaultSort('-created_at')
            ->get();

        $exportFilePath = (new FastExcel($query))
        ->export($this->fileName, function ($profile) {
            return [
                'id' => $profile->id,
                'user_id' => $profile->user_id,
                'first_name' => $profile->first_name,
                'last_name' => $profile->last_name,
                'email' => $profile->email,
                'phone' => $profile->phone,
                'mobile' => $profile->mobile,
                'zip' => $profile->zip,
                'referent_department' => $profile->referent_department,
                'referent_region' => $profile->referent_region,
                'tete_de_reseau_id' => $profile->tete_de_reseau_id,
                'service_civique' => $profile->service_civique,
                'is_visible' => $profile->is_visible,
                'created_at' => $profile->created_at,
                'updated_at' => $profile->updated_at,
            ];
        });

        Storage::disk('s3')->put($this->filePath, file_get_contents($exportFilePath));

        File::delete($exportFilePath);
    }
}

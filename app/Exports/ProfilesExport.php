<?php

namespace App\Exports;

use App\Filters\FiltersDisponibility;
use App\Filters\FiltersMatchMission;
use App\Filters\FiltersProfileCollectivity;
use App\Filters\FiltersProfileDepartment;
use App\Filters\FiltersProfileMinParticipations;
use App\Filters\FiltersProfilePostalCode;
use App\Models\Profile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileRole;
use App\Filters\FiltersProfileSkill;
use App\Filters\FiltersProfileTag;
use App\Filters\FiltersProfileZips;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfilesExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return QueryBuilder::for(Profile::role($this->role))
        ->allowedAppends('roles', 'has_user', 'skills', 'domaines', 'referent_waiting_actions', 'referent_region_waiting_actions', 'responsable_waiting_actions')
        ->allowedFilters(
            AllowedFilter::custom('search', new FiltersProfileSearch),
            AllowedFilter::custom('postal_code', new FiltersProfilePostalCode),
            AllowedFilter::custom('zips', new FiltersProfileZips),
            AllowedFilter::custom('role', new FiltersProfileRole),
            AllowedFilter::custom('domaines', new FiltersProfileTag),
            AllowedFilter::custom('collectivity', new FiltersProfileCollectivity),
            AllowedFilter::custom('department', new FiltersProfileDepartment),
            AllowedFilter::custom('disponibilities', new FiltersDisponibility),
            AllowedFilter::custom('skills', new FiltersProfileSkill),
            AllowedFilter::custom('match_mission', new FiltersMatchMission),
            AllowedFilter::exact('is_visible'),
            AllowedFilter::custom('min_participations', new FiltersProfileMinParticipations),
            AllowedFilter::exact('referent_department'),
            'referent_region'
        )
            ->defaultSort('-created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'user_id',
            'full_name',
            'first_name',
            'last_name',
            'email',
            'phone',
            'mobile',
            'zip',
            'referent_department',
            'referent_region',
            'reseau_id',
            'service_civique',
            'is_visible',
            'created_at',
            'updated_at',
        ];
    }

    public function map($profile): array
    {
        return [
            $profile->id,
            $profile->user->id,
            $profile->full_name,
            $profile->first_name,
            $profile->last_name,
            $profile->email,
            $profile->phone,
            $profile->mobile,
            $profile->zip,
            $profile->referent_department,
            $profile->referent_region,
            $profile->reseau_id,
            $profile->service_civique,
            $profile->is_visible,
            $profile->created_at,
            $profile->updated_at,
        ];
    }
}

<?php

namespace App\Exports;

use App\Filters\FiltersDisponibility;
use App\Filters\FiltersMatchMission;
use App\Filters\FiltersProfileCollectivity;
use App\Filters\FiltersProfileDepartment;
use App\Filters\FiltersProfileMinParticipations;
use App\Filters\FiltersProfilePostalCode;
use App\Models\Profile;
use Maatwebsite\Excel\Concerns\FromQuery;
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

class ProfilesExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function query()
    {
        return QueryBuilder::for(Profile::role($this->role))
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
        );
    }

    public function headings(): array
    {
        return [
            'id',
            'prenom',
            'nom',
            'email',
            'telephone',
            'mobile',
            'code_postal',
            'date_creation',
        ];
    }

    public function map($profile): array
    {
        return [
            $profile->id,
            $profile->first_name,
            $profile->last_name,
            $profile->email,
            $profile->phone,
            $profile->mobile,
            $profile->zip,
            $profile->created_at,
        ];
    }
}

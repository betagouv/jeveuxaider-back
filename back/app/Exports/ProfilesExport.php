<?php

namespace App\Exports;

use App\Filters\FiltersDisponibility;
use App\Filters\FiltersMatchMission;
use App\Filters\FiltersProfileMinParticipations;
use App\Models\Profile;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileRole;
use App\Filters\FiltersProfileSkill;
use App\Filters\FiltersProfileTag;
use App\Filters\FiltersProfileZips;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfilesExport implements FromCollection, WithMapping, WithHeadings, ShouldQueue
{
    use Exportable;

    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function collection()
    {
        return QueryBuilder::for(Profile::role($this->role))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('zips', new FiltersProfileZips),
                AllowedFilter::custom('role', new FiltersProfileRole),
                AllowedFilter::custom('domaines', new FiltersProfileTag),
                AllowedFilter::custom('disponibilities', new FiltersDisponibility),
                //AllowedFilter::custom('skills', new FiltersProfileSkill),
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
            'prenom',
            'email',
            'code_postal',
        ];
    }

    public function map($profile): array
    {
        return [
            $profile->id,
            $profile->first_name,
            $profile->email,
            $profile->zip,
        ];
    }
}

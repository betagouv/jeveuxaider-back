<?php

namespace App\Exports;

use App\Filters\FiltersProfileMinParticipations;
use App\Models\Profile;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileRole;
use App\Sorts\ProfileParticipationsValidatedCountSort;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Spatie\QueryBuilder\AllowedSort;

class ProfilesExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return QueryBuilder::for(Profile::role($this->request->header('Context-Role')))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('role', new FiltersProfileRole),
                'department',
                'referent_department',
                'referent_region',
                'zip',
                AllowedFilter::exact('is_visible'),
                AllowedFilter::custom('min_participations', new FiltersProfileMinParticipations)
            )
            ->defaultSort('-created_at')
            ->allowedSorts([
                'created_at',
                AllowedSort::custom('participations_validated_count', new ProfileParticipationsValidatedCountSort()),
            ]);
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

<?php

namespace App\Exports;

use App\Filters\FiltersProfileMinParticipations;
use App\Filters\FiltersProfileRole;
use App\Filters\FiltersProfileSearch;
use App\Models\Profile;
use App\Sorts\ProfileParticipationsValidatedCountSort;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

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

        if($this->isResponsableExport()){
            $queryBuilder = Profile::role($this->request->header('Context-Role'))->with(['structures']);
        } else {
            $queryBuilder = Profile::role($this->request->header('Context-Role'));
        }

        return QueryBuilder::for($queryBuilder)
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('role', new FiltersProfileRole),
                AllowedFilter::exact('department'),
                AllowedFilter::exact('referent_department'),
                AllowedFilter::exact('referent_region'),
                AllowedFilter::exact('zip'),
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

        if($this->isResponsableExport()){
            return [
                'id',
                'prenom',
                'nom',
                'email',
                'phone',
                'mobile',
                'code_postal',
                'structure_id',
                'structure_nom',
            ];
        }

        return [
            'id',
            'prenom',
            'email',
            'code_postal',
        ];
    }

    public function map($profile): array
    {
        if($this->isResponsableExport()){
            $structure = $profile->structures->first();
            return [
                $profile->id,
                $profile->first_name,
                $profile->last_name,
                $profile->email,
                $profile->phone,
                $profile->mobile,
                $profile->zip,
                $structure ? $structure->id : null,
                $structure ? $structure->name : null,
            ];
        }

        return [
            $profile->id,
            $profile->first_name,
            $profile->email,
            $profile->zip,
        ];
    }

    private function isResponsableExport(): bool
    {
        return $this->request->has('filter.role') && $this->request->input('filter.role') == 'responsable';
    }
}

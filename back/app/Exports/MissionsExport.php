<?php

namespace App\Exports;

use App\Filters\FiltersMissionIsTemplate;
use Illuminate\Http\Request;
use App\Models\Mission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersMissionPlacesLeft;
use App\Filters\FiltersMissionPublicsVolontaires;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;

class MissionsExport implements FromCollection, WithMapping, WithHeadings, ShouldQueue
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
        return QueryBuilder::for(Mission::role($this->role))
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('id'),
                AllowedFilter::exact('department'),
                AllowedFilter::exact('responsable.id'),
                AllowedFilter::exact('template_id'),
                AllowedFilter::exact('structure.id'),
                AllowedFilter::exact('structure.name'),
                AllowedFilter::scope('ofReseau'),
                AllowedFilter::scope('domaine'),
                AllowedFilter::custom('place', new FiltersMissionPlacesLeft),
                AllowedFilter::custom('publics_volontaires', new FiltersMissionPublicsVolontaires),
                AllowedFilter::custom('search', new FiltersMissionSearch),
                AllowedFilter::scope('available'),
                AllowedFilter::custom('is_template', new FiltersMissionIsTemplate),
            ])
            ->defaultSort('-created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'structure_id',
            'nom',
            'statut',
            'description',
            'type',
            'periodicite',
            'adresse_complete',
            'adresse',
            'code_postal',
            'ville',
            'departement',
            'pays',
            'latitude',
            'longitude',
            'date_debut',
            'date_fin',
            'nb_participations_max',
            'nb_places_restantes',
            'engagement_duree',
            'engagement_periode',
            'date_creation',
            'date_modification',
        ];
    }

    public function map($mission): array
    {
        return [
            $mission->id,
            $mission->structure_id,
            $mission->name,
            $mission->state,
            $mission->description,
            $mission->type,
            $mission->periodicite,
            $mission->full_address,
            $mission->address,
            $mission->zip,
            $mission->city,
            $mission->department,
            $mission->country,
            $mission->latitude,
            $mission->longitude,
            $mission->start_date,
            $mission->end_date,
            $mission->participations_max,
            $mission->places_left,
            $mission->commitment__duration,
            $mission->commitment__time_period,
            $mission->created_at,
            $mission->updated_at,
        ];
    }
}

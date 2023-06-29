<?php

namespace App\Exports;

use App\Filters\FiltersDoesntHaveTags;
use App\Filters\FiltersMissionDate;
use App\Filters\FiltersMissionIsTemplate;
use App\Filters\FiltersMissionPlacesLeft;
use App\Filters\FiltersMissionPublicsVolontaires;
use App\Filters\FiltersMissionPublicsBeneficiaires;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersTags;
use App\Models\Mission;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MissionsExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return QueryBuilder::for(Mission::role($this->request->header('Context-Role'))->with(['structure']))
            ->allowedFilters([
                AllowedFilter::exact('state'),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('department'),
                AllowedFilter::exact('zip'),
                AllowedFilter::exact('responsable.id'),
                AllowedFilter::exact('template.id'),
                AllowedFilter::exact('structure.id'),
                AllowedFilter::exact('structure.name'),
                AllowedFilter::exact('structure.statut_juridique'),
                AllowedFilter::exact('structure.reseaux.id'),
                AllowedFilter::exact('structure.reseaux.name'),
                AllowedFilter::exact('is_snu_mig_compatible'),
                AllowedFilter::scope('ofDomaine'),
                AllowedFilter::scope('ofTerritoire'),
                AllowedFilter::scope('ofActivity'),
                AllowedFilter::scope('hasActivity'),
                AllowedFilter::scope('hasTemplate'),
                AllowedFilter::scope('hasCreneaux'),
                AllowedFilter::custom('place', new FiltersMissionPlacesLeft),
                AllowedFilter::custom('date', new FiltersMissionDate),
                AllowedFilter::custom('publics_volontaires', new FiltersMissionPublicsVolontaires),
                AllowedFilter::custom('publics_beneficiaires', new FiltersMissionPublicsBeneficiaires),
                AllowedFilter::custom('search', new FiltersMissionSearch),
                AllowedFilter::scope('available'),
                AllowedFilter::custom('is_template', new FiltersMissionIsTemplate),
                AllowedFilter::exact('is_autonomy'),
                AllowedFilter::custom('tags', new FiltersTags),
                AllowedFilter::custom('no_tags', new FiltersDoesntHaveTags),
                AllowedFilter::exact('is_active'),
                AllowedFilter::scope('ofResponsable'),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                'created_at',
                'updated_at',
                'places_left',
            ]);
    }

    public function headings(): array
    {
        return [
            'id',
            'structure_id',
            'structure_nom',
            'structure_phone',
            'structure_email',
            'nom',
            'statut',
            // 'description',
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
            $mission->structure ? $mission->structure->name : null,
            $mission->structure ? $mission->structure->phone : null,
            $mission->structure ? $mission->structure->email : null,
            $mission->name,
            $mission->state,
            // $mission->description,
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

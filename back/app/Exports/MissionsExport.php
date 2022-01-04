<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\Models\Mission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersMissionLieu;
use App\Filters\FiltersMissionPlacesLeft;
use App\Filters\FiltersMissionDomaine;
use App\Filters\FiltersMissionDates;

class MissionsExport implements FromCollection, WithMapping, WithHeadings
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return QueryBuilder::for(Mission::role($this->request->header('Context-Role')))
            ->allowedFilters([
                'name',
                'state',
                'type',
                'structure.statut_juridique',
                AllowedFilter::exact('department'),
                AllowedFilter::exact('template_id'),
                AllowedFilter::exact('structure_id'),
                AllowedFilter::exact('id'),
                AllowedFilter::custom('search', new FiltersMissionSearch),
                AllowedFilter::custom('lieu', new FiltersMissionLieu),
                AllowedFilter::custom('place', new FiltersMissionPlacesLeft),
                AllowedFilter::custom('dates', new FiltersMissionDates),
                AllowedFilter::custom('domaine', new FiltersMissionDomaine),
                AllowedFilter::exact('responsable_id'),
                AllowedFilter::scope('minimum_commitment'),
                AllowedFilter::scope('of_reseau')
            ])
            ->defaultSort('-created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'nom',
            'statut',
            'description',
            'type',
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
            'date_creation',
            'date_modification',
            'structure',
            'structure_id',
            'nb_participations_max',
            'nb_places_restantes',
            'dates_infos',
            'responsable',
            'responsable_id',
            'responsable_email',
            'domaine',
            'template'
        ];
    }

    public function map($mission): array
    {
        return [
            $mission->id,
            $mission->name,
            $mission->state,
            $mission->description,
            $mission->type,
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
            $mission->created_at,
            $mission->updated_at,
            $mission->structure ? $mission->structure->name : '',
            $mission->structure ? $mission->structure->id : '',
            $mission->participations_max,
            $mission->places_left,
            $mission->dates_infos,
            $mission->responsable ? $mission->responsable->full_name : '',
            $mission->responsable ? $mission->responsable->id : '',
            $mission->responsable ? $mission->responsable->email : '',
            $mission->template ? $mission->template->domaine->name : $mission->domaine->name,
            $mission->template ? $mission->template->title : '',
        ];
    }
}

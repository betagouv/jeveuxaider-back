<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\Models\Mission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Filters\FiltersMissionCeu;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersMissionLieu;
use App\Filters\FiltersMissionPlacesLeft;
use App\Filters\FiltersMissionDomaine;

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
                'format',
                'type',
                'department',
                AllowedFilter::exact('template_id'),
                AllowedFilter::custom('ceu', new FiltersMissionCeu),
                AllowedFilter::custom('search', new FiltersMissionSearch),
                AllowedFilter::custom('lieu', new FiltersMissionLieu),
                AllowedFilter::custom('place', new FiltersMissionPlacesLeft),
                AllowedFilter::custom('domaine', new FiltersMissionDomaine),
            ])
            ->defaultSort('-created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'state',
            'description',
            'format',
            'type',
            'full_address',
            'address',
            'zip',
            'city',
            'department',
            'country',
            'latitude',
            'longitude',
            'start_date',
            'end_date',
            'created_at',
            'updated_at',
            'structure',
            'structure_id',
            'participations_max',
            'places_left',
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
            $mission->format,
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

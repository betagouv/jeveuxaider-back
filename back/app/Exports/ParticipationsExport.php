<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\Models\Participation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersParticipationSearch;
use App\Filters\FiltersParticipationLieu;
use App\Filters\FiltersParticipationDomaine;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipationsExport implements FromCollection, WithMapping, WithHeadings
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
        return QueryBuilder::for(Participation::role($this->request->header('Context-Role')))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationSearch),
                AllowedFilter::custom('lieu', new FiltersParticipationLieu),
                AllowedFilter::custom('domaine', new FiltersParticipationDomaine),
                'state',
                AllowedFilter::exact('mission.department'),
                'mission.type',
                'mission.name',
                AllowedFilter::exact('mission.template_id'),
                AllowedFilter::exact('mission.structure_id'),
                AllowedFilter::exact('mission.id'),
                AllowedFilter::exact('mission.responsable_id'),
            )
            ->defaultSort('-created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'structure_id',
            'structure_nom',
            'mission_id',
            'mission_nom',
            'responsable_nom',
            'responsable_email',
            'profile_id',
            'prenom',
            'nom',
            'mobile',
            'email',
            'code_postal',
            'date_anniversaire',
            'statut',
            'date_creation',
            'date_modification'
        ];
    }

    public function map($participation): array
    {
        $hidden = ($participation->mission && $participation->mission->state == 'Signalée') && $this->request->header('Context-Role') == 'responsable'
            ? true : false;

        return [
            $participation->id,
            $participation->mission ? $participation->mission->structure_id : '',
            $participation->mission ? ($participation->mission->structure ? $participation->mission->structure->name : '') : '',
            $participation->mission_id,
            $participation->mission ? $participation->mission->name : '',
            $participation->mission && $participation->mission->responsable ? $participation->mission->responsable->full_name : '',
            $participation->mission && $participation->mission->responsable ? $participation->mission->responsable->email : '',
            $participation->profile_id,
            $participation->profile && !$hidden ? $participation->profile->first_name : '',
            $participation->profile && !$hidden ? $participation->profile->last_name : '',
            $participation->profile && !$hidden ? $participation->profile->mobile : '',
            $participation->profile && !$hidden ? $participation->profile->email : '',
            $participation->profile && !$hidden ? $participation->profile->zip : '',
            $participation->profile && !$hidden ? $participation->profile->birthday : '',
            $participation->state,
            $participation->created_at,
            $participation->updated_at
        ];
    }
}

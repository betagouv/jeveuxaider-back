<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\Models\Participation;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersParticipationSearch;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipationsExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return QueryBuilder::for(Participation::role($this->request->header('Context-Role'))->with(['profile', 'mission']))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationSearch),
                AllowedFilter::exact('mission.id'),
                AllowedFilter::exact('mission.name'),
                AllowedFilter::exact('mission.department'),
                AllowedFilter::exact('mission.structure.name'),
                AllowedFilter::exact('mission.structure.id'),
                AllowedFilter::exact('mission.template.id'),
                AllowedFilter::exact('profile.id'),
                AllowedFilter::scope('ofReseau'),
                AllowedFilter::scope('ofTerritoire'),
                AllowedFilter::scope('ofActivity'),
                AllowedFilter::scope('ofDomaine'),
                AllowedFilter::scope('ofResponsable'),
                'state',
                'mission.zip',
                'mission.type',
            )
            ->defaultSort('-created_at');
    }

    public function headings(): array
    {
        return [
            'id',
            'statut',
            'mission_id',
            'mission_nom',
            'mission_date_debut',
            'mission_date_fin',
            'profile_id',
            'benevole_prenom',
            'benevole_nom',
            'benevole_mobile',
            'benevole_email',
            'benevole_code_postal',
            'benevole_date_anniversaire',
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
            $participation->state,
            $participation->mission_id,
            $participation->mission ? $participation->mission->name : '',
            $participation->mission ? $participation->mission->start_date : '',
            $participation->mission ? $participation->mission->end_date : '',
            $participation->profile_id,
            $participation->profile && !$hidden ? $participation->profile->first_name : '',
            $participation->profile && !$hidden ? $participation->profile->last_name : '',
            $participation->profile && !$hidden ? $participation->profile->mobile : '',
            $participation->profile && !$hidden ? $participation->profile->email : '',
            $participation->profile && !$hidden ? $participation->profile->zip : '',
            $participation->profile && !$hidden ? $participation->profile->birthday : '',
            $participation->created_at,
            $participation->updated_at
        ];
    }
}

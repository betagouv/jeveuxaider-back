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
                'mission.department',
                'mission.type',
                'mission.name',
                'mission.template_id',
                AllowedFilter::exact('mission.id')
            )
            ->defaultSort('-created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'mission_id',
            'mission_name',
            'responsable_name',
            'responsable_email',
            'profile_id',
            'first_name',
            'last_name',
            'mobile',
            'email',
            'zip',
            'birthday',
            'state',
            'created_at',
            'updated_at'
        ];
    }

    public function map($participation): array
    {
        $hidden = (($participation->mission && $participation->mission->state == 'Signalée')
         || $participation->state == 'Mission signalée') && $this->request->header('Context-Role') == 'responsable'
         ? true : false;

        return [
            $participation->id,
            $participation->mission_id,
            $participation->mission ? $participation->mission->name : '',
            $participation->mission && $participation->mission->tuteur ? $participation->mission->tuteur->full_name : '',
            $participation->mission && $participation->mission->tuteur ? $participation->mission->tuteur->email : '',
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

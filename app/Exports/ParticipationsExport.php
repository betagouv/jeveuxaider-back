<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\Models\Participation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersParticipationSearch;
use App\Filters\FiltersParticipationLieu;

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
                'state',
                'mission.department',
                'mission.type',
                'mission.name'
            )
            ->defaultSort('-updated_at')
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
            'id' => $participation->id,
            'mission_id' => $participation->mission_id,
            'mission_name' => $participation->mission ? $participation->mission->name : '',
            'responsable_name' => $participation->mission && $participation->mission->tuteur ? $participation->mission->tuteur->full_name : '',
            'responsable_email' => $participation->mission && $participation->mission->tuteur ? $participation->mission->tuteur->email : '',
            'profile_id' => $participation->profile_id,
            'first_name' => $participation->profile && !$hidden ? $participation->profile->first_name : '',
            'last_name' => $participation->profile && !$hidden ? $participation->profile->last_name : '',
            'mobile' => $participation->profile && !$hidden ? $participation->profile->mobile : '',
            'email' => $participation->profile && !$hidden ? $participation->profile->email : '',
            'zip' => $participation->profile && !$hidden ? $participation->profile->zip : '',
            'state' => $participation->state,
            'created_at' => $participation->created_at,
            'updated_at' => $participation->updated_at
        ];
    }
}

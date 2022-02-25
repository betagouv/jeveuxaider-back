<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\Models\Participation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersParticipationSearch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ParticipationsExport implements FromCollection, WithMapping, WithHeadings, ShouldQueue
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
        return QueryBuilder::for(Participation::role($this->role)->with(['profile', 'mission']))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationSearch),
                AllowedFilter::exact('mission_id'),
                AllowedFilter::exact('mission.department'),
                AllowedFilter::scope('ofReseau'),
                'state',
                'mission.zip',
                'mission.type',
                'mission.structure.name'
            )
            ->defaultSort('-created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'statut',
            'mission_id',
            'mission_nom',
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
        $hidden = ($participation->mission && $participation->mission->state == 'Signalée') && $this->role == 'responsable'
            ? true : false;

        return [
            $participation->id,
            $participation->state,
            $participation->mission_id,
            $participation->mission ? $participation->mission->name : '',
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

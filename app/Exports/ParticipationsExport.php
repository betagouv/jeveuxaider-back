<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\Models\Participation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersParticipationSearch;
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
                'state'
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
            'state',
            'created_at',
            'updated_at'
        ];
    }

    public function map($participation): array
    {
        return [
            'id' => $participation->id,
            'mission_id' => $participation->mission_id,
            'mission_name' => $participation->mission->name,
            'responsable_name' => $participation->mission->tuteur ? $participation->mission->tuteur->full_name : '',
            'responsable_email' => $participation->mission->tuteur ? $participation->mission->tuteur->email : '',
            'profile_id' => $participation->profile_id,
            'first_name' => $participation->profile->first_name,
            'last_name' => $participation->profile->last_name,
            'mobile' => $participation->profile->mobile,
            'state' => $participation->state,
            'created_at' => $participation->created_at,
            'updated_at' => $participation->updated_at
        ];
    }
}

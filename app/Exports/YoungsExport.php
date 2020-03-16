<?php

namespace App\Exports;

use App\Filters\FiltersYoungContext;
use App\Filters\FiltersYoungLieu;
use Illuminate\Http\Request;
use App\Models\Young;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersYoungSearch;

class YoungsExport implements FromCollection, WithMapping, WithHeadings
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
        return QueryBuilder::for(Young::role($this->request->header('Context-Role')))
            ->allowedFilters(
                'department',
                'state',
                'mission_format',
                AllowedFilter::custom('search', new FiltersYoungSearch),
                AllowedFilter::custom('lieu', new FiltersYoungLieu),
                AllowedFilter::custom('context', new FiltersYoungContext)
            )
            ->defaultSort('-updated_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'first_name',
            'last_name',
            'email',
            'phone',
            'department',
            'address',
            'zip',
            'city',
            'latitude',
            'longitude',
            'regular_city',
            'regular_latitude',
            'regular_longitude',
            'engaged',
            'engaged_structure',
            'interet_defense',
            'interet_defense_type',
            'interet_defense_domaine',
            'interet_defense_motivation',
            'interet_securite',
            'interet_securite_domaine',
            'interet_solidarite',
            'interet_sante',
            'interet_education',
            'interet_culture',
            'interet_sport',
            'interet_environnement',
            'interet_citoyennete',
            'mission_format',
            'mission_autonome_projet',
            'mission_autonome_structure',
            'contraintes',
            'situation',
            'genre',
            'notes',
            'mission_id',
            'state',
            'created_at',
            'updated_at'
        ];
    }

    public function map($young): array
    {
        return [
            'id' => $young->id,
            'first_name' => $young->first_name,
            'last_name' => $young->last_name,
            'email' => $young->email,
            'phone' => $young->phone,
            'department' => $young->department,
            'address' => $young->address,
            'zip' => $young->zip,
            'city' => $young->city,
            'latitude' => $young->latitude,
            'longitude' => $young->longitude,
            'regular_city' => $young->regular_city,
            'regular_latitude' => $young->regular_latitude,
            'regular_longitude' => $young->regular_longitude,
            'engaged' => $young->engaged,
            'engaged_structure' => $young->engaged_structure,
            'interet_defense' => $young->interet_defense,
            'interet_defense_type' => $young->interet_defense_type,
            'interet_defense_domaine' => $young->interet_defense_domaine,
            'interet_defense_motivation' => $young->interet_defense_motivation,
            'interet_securite' => $young->interet_securite,
            'interet_securite_domaine' => $young->interet_securite_domaine,
            'interet_solidarite' => $young->interet_solidarite,
            'interet_sante' => $young->interet_sante,
            'interet_education' => $young->interet_education,
            'interet_culture' => $young->interet_culture,
            'interet_sport' => $young->interet_sport,
            'interet_environnement' => $young->interet_environnement,
            'interet_citoyennete' => $young->interet_citoyennete,
            'mission_format' => $young->mission_format,
            'mission_autonome_projet' => $young->mission_autonome_projet,
            'mission_autonome_structure' => $young->mission_autonome_structure,
            'contraintes' => $young->contraintes,
            'situation' => $young->situation,
            'genre' => $young->genre,
            'notes' => $young->notes,
            'mission_id' => $young->mission_id,
            'state' => $young->state,
            'created_at' => $young->created_at,
            'updated_at' => $young->updated_at,
        ];
    }
}

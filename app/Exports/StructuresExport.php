<?php

namespace App\Exports;

use App\Filters\FiltersStructureSearch;
use App\Models\Structure;
use App\Sorts\StructureMissionsCountSort;
use App\Sorts\StructurePlacesLeftSort;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class StructuresExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return QueryBuilder::for(Structure::role($this->request->header('Context-Role')))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersStructureSearch),
                AllowedFilter::exact('department'),
                AllowedFilter::exact('state'),
                AllowedFilter::exact('statut_juridique'),
                AllowedFilter::exact('reseaux.id'),
                AllowedFilter::exact('reseaux.name'),
                AllowedFilter::scope('ofReseau'),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                'created_at',
                'updated_at',
                AllowedSort::custom('missions_count', new StructureMissionsCountSort()),
                AllowedSort::custom('places_left', new StructurePlacesLeftSort()),
            ]);
    }

    public function headings(): array
    {
        return [
            'id',
            'nom',
            'rna',
            'statut',
            'statut_juridique',
            'association_types',
            'structure_publique_type',
            'structure_publique_etat_type',
            'structure_privee_type',
            'structure_phone',
            'structure_email',
            'adresse_complete',
            'adresse',
            'departement',
            'latitude',
            'longitude',
            'code_postal',
            'ville',
            'pays',
            'site_internet',
            'instagram',
            'facebook',
            'twitter',
            'date_creation',
            'date_modification',
        ];
    }

    public function map($structure): array
    {
        return [
            $structure->id,
            $structure->name,
            $structure->rna,
            $structure->state,
            $structure->statut_juridique,
            $structure->association_types ? implode(', ', $structure->association_types) : null,
            $structure->structure_publique_type,
            $structure->structure_publique_etat_type,
            $structure->structure_privee_type,
            $structure->phone,
            $structure->email,
            $structure->full_address,
            $structure->address,
            $structure->department,
            $structure->latitude,
            $structure->longitude,
            $structure->zip,
            $structure->city,
            $structure->country,
            $structure->website,
            $structure->instagram,
            $structure->facebook,
            $structure->twitter,
            $structure->created_at,
            $structure->updated_at,
        ];
    }
}

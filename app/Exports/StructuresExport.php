<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\Models\Structure;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Filters\FiltersStructureCeu;
use App\Filters\FiltersStructureSearch;
use App\Filters\FiltersStructureLieu;

class StructuresExport implements FromCollection, WithMapping, WithHeadings
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
        return QueryBuilder::for(Structure::role($this->request->header('Context-Role')))
            ->allowedFilters([
                'department',
                'statut_juridique',
                'state',
                AllowedFilter::custom('ceu', new FiltersStructureCeu),
                AllowedFilter::custom('lieu', new FiltersStructureLieu),
                AllowedFilter::custom('search', new FiltersStructureSearch),
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
            'logo',
            'statut_juridique',
            'association_types',
            'structure_publique_type',
            'structure_publique_etat_type',
            'structure_privee_type',
            'ceu',
            'siret',
            'description',
            'is_reseau',
            'reseau_id',
            'full_address',
            'address',
            'department',
            'latitude',
            'longitude',
            'zip',
            'city',
            'country',
            'website',
            'instagram',
            'facebook',
            'twitter',
            'created_at',
            'updated_at',
            'missions'
        ];
    }

    public function map($structure): array
    {
        return [
            $structure->id,
            $structure->name,
            $structure->state,
            $structure->logo,
            $structure->statut_juridique,
            $structure->association_types,
            $structure->structure_publique_type,
            $structure->structure_publique_etat_type,
            $structure->structure_privee_type,
            $structure->ceu,
            $structure->siret,
            $structure->description,
            $structure->is_reseau,
            $structure->reseau_id,
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
            $structure->missions->count()
        ];
    }
}

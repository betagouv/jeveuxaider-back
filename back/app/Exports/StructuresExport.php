<?php

namespace App\Exports;

use App\Models\Structure;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Filters\FiltersStructureSearch;
use App\Filters\FiltersStructureWithRna;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;

class StructuresExport implements FromCollection, WithMapping, WithHeadings, ShouldQueue
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
        return QueryBuilder::for(Structure::role($this->role))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersStructureSearch),
                AllowedFilter::exact('department'),
                'state',
                'statut_juridique',
                // AllowedFilter::custom('rna', new FiltersStructureWithRna),
                AllowedFilter::scope('ofReseau'),
            ])
            ->defaultSort('-created_at')
            ->get();
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
        // $responsable = $structure->responsables->first();

        return [
            $structure->id,
            $structure->name,
            $structure->rna,
            $structure->state,
            $structure->statut_juridique,
            $structure->association_types,
            $structure->structure_publique_type,
            $structure->structure_publique_etat_type,
            $structure->structure_privee_type,
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

<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Filters\FiltersReseauSearch;
use App\Models\Reseau;

class ReseauxExport implements FromCollection, WithMapping, WithHeadings, ShouldQueue
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return QueryBuilder::for(Reseau::withCount(['structures','missions','missionTemplates']))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersReseauSearch),
                AllowedFilter::exact('is_published'),
                AllowedFilter::exact('id'),
                'name',
            ])
            ->defaultSort('-created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'suffix_title',
            'slug',
            'adresse_complete',
            'adresse',
            'departement',
            'latitude',
            'longitude',
            'code_postal',
            'ville',
            'pays',
            'is_published',
            'full_url',
            'nb_antennes',
            'nb_missions',
            'nb_modeles_mission',
        ];
    }

    public function map($reseau): array
    {

        return [
            $reseau->id,
            $reseau->name,
            $reseau->suffix_title,
            $reseau->slug,
            $reseau->adresse_complete,
            $reseau->adresse,
            $reseau->departement,
            $reseau->latitude,
            $reseau->longitude,
            $reseau->code_postal,
            $reseau->ville,
            $reseau->pays,
            $reseau->is_published,
            $reseau->full_url,
            $reseau->structures_count,
            $reseau->missions_count,
            $reseau->mission_templates_count,
        ];
    }
}

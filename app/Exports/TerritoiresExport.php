<?php

namespace App\Exports;

use App\Models\Territoire;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Filters\FiltersTerritoireSearch;
use Maatwebsite\Excel\Concerns\FromQuery;

class TerritoiresExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return QueryBuilder::for(Territoire::class)
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('is_published'),
                AllowedFilter::custom('search', new FiltersTerritoireSearch),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                'created_at',
                'updated_at',
            ]);
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'suffix_title',
            'slug',
            'type',
            'department',
            'zips',
            'state',
            'is_published',
            'full_url',
            'structure_id'
        ];
    }

    public function map($territoire): array
    {

        return [
            $territoire->id,
            $territoire->name,
            $territoire->suffix_title,
            $territoire->slug,
            $territoire->type,
            $territoire->department,
            $territoire->zips,
            $territoire->state,
            $territoire->is_published,
            $territoire->full_url,
            $territoire->structure_id,
        ];
    }
}

<?php

namespace App\Exports;

use App\Filters\FiltersTerritoireSearch;
use App\Models\Territoire;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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
            'structure_id',
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

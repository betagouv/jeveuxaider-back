<?php

namespace App\Exports;

use App\Models\Territoire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Filters\FiltersTerritoireSearch;

class TerritoiresExport implements FromCollection, WithMapping, WithHeadings, ShouldQueue
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return QueryBuilder::for(Territoire::class)
            ->allowedFilters([
                'state',
                'type',
                AllowedFilter::exact('is_published'),
                AllowedFilter::custom('search', new FiltersTerritoireSearch),
            ])
            ->allowedAppends([
                'full_url',
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
            'type',
            'department',
            'zips',
            'state',
            'is_published',
            'missing_fields',
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
            $territoire->missing_fields,
            $territoire->full_url,
            $territoire->structure_id,
        ];
    }
}

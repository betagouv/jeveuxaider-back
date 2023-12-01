<?php

namespace App\Exports;

use App\Filters\FiltersTerritoireSearch;
use App\Models\Territoire;
use App\Models\User;
use App\Notifications\UserHasExportedDatas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TerritoiresExport implements FromQuery, WithMapping, WithHeadings, WithEvents
{
    use Exportable;
    use RegistersEventListeners;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function afterSheet(AfterSheet $event)
    {
        $nbRows = $event->sheet->getHighestRow() > 0 ? $event->sheet->getHighestRow() - 1 : 0;

        activity('export')
            ->event('exported')
            ->withProperties([
                'type' => 'territoires',
                'items_count' => $nbRows,
                'filter' => request()->input('filter')
            ])
            ->log("exported");

        $currentUser = User::find(Auth::guard('api')->user()->id);

        Notification::route('slack', config('services.slack.hook_url'))
            ->notify(new UserHasExportedDatas($currentUser, 'territoires', $nbRows));
    }

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
                AllowedFilter::custom('search', new FiltersTerritoireSearch()),
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

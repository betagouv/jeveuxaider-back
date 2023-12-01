<?php

namespace App\Exports;

use App\Filters\FiltersReseauSearch;
use App\Models\Reseau;
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

class ReseauxExport implements FromQuery, WithMapping, WithHeadings, WithEvents
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
                'type' => 'reseaux',
                'items_count' => $nbRows,
                'filter' => request()->input('filter')
            ])
            ->log("exported");

        $currentUser = User::find(Auth::guard('api')->user()->id);

        Notification::route('slack', config('services.slack.hook_url'))
            ->notify(new UserHasExportedDatas($currentUser, 'reseaux', $nbRows));
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return QueryBuilder::for(Reseau::withCount(['structures', 'missions', 'missionTemplates']))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersReseauSearch()),
                AllowedFilter::exact('is_published'),
                AllowedFilter::exact('id'),
                'name',
            ])
            ->defaultSort('-created_at');
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

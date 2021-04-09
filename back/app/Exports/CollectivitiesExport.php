<?php

namespace App\Exports;

use App\Filters\FiltersCollectivitiesDepartment;
use App\Filters\FiltersCollectivitySearch;
use App\Models\Structure;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Collectivity;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CollectivitiesExport implements FromCollection, WithHeadings
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
        $collectivities = QueryBuilder::for(Collectivity::role($this->role)->where('type', 'commune'))
            ->allowedFilters([
                'state',
                AllowedFilter::exact('published'),
                AllowedFilter::custom('search', new FiltersCollectivitySearch),
                AllowedFilter::custom('department', new FiltersCollectivitiesDepartment),
            ])
            ->defaultSort('name')
            ->get();

        $datas = collect();

        foreach ($collectivities as $collectivity) {
            $missionsAvailableCollection = Mission::whereIn('zip', $collectivity->zips)
                ->hasPlacesLeft()
                ->available()
                ->get();

            $missionsCollection = Mission::whereIn('zip', $collectivity->zips)
                ->whereIn('state', ['Validée', 'Terminée'])
                ->get();

            $places_available_left = $missionsCollection->where('state', 'Validée')->sum('places_left');
            $places_offered = $missionsCollection->where('state', 'Validée')->sum('participations_max');
            $total_participations_max = $missionsCollection->sum('participations_max');
            $datas->push([
                'id' => $collectivity->id,
                'nom' => $collectivity->name,
                'en_ligne' => $collectivity->published,
                'nb_missions' => Mission::whereIn('zip', $collectivity->zips)->count(),
                'nb_missions_validees' => Mission::whereIn('zip', $collectivity->zips)->where('state', 'Validée')->count(),
                'nb_missions_refusees' => Mission::whereIn('zip', $collectivity->zips)->where('state', 'Refusée')->count(),
                'nb_missions_terminees' => Mission::whereIn('zip', $collectivity->zips)->where('state', 'Terminée')->count(),
                'nb_structures' => Structure::whereIn('zip', $collectivity->zips)->count(),
                'nb_participations' => Participation::whereHas('mission', function (Builder $query) use ($collectivity) {
                    $query->whereIn('zip', $collectivity->zips);
                })->count(),
                'nb_volontaires' => Profile::whereIn('zip', $collectivity->zips)->count(),
                'nb_service_civique' => Profile::whereIn('zip', $collectivity->zips)
                    ->whereHas('user', function (Builder $query) {
                        $query->where('service_civique', true);
                    })->count(),
                'nb_missions_en_ligne' => $missionsAvailableCollection->count(),
                'nb_organisations_actives' => $missionsAvailableCollection->pluck('structure_id')->unique()->count(),
                'nb_places_disponibles' => $missionsAvailableCollection->sum('places_left'),
                'nb_places_offertes' => $total_participations_max,
                'taux_occupation' => $places_offered ? round(($places_available_left / $places_offered) * 100) : 0,
            ]);
        }

        return $datas;
    }

    public function headings(): array
    {
        return [
            'id',
            'nom',
            'en_ligne',
            'nb_missions',
            'nb_missions_validees',
            'nb_missions_refusees',
            'nb_missions_terminees',
            'nb_structures',
            'nb_participations',
            'nb_volontaires',
            'nb_service_civique',
            'nb_missions_en_ligne',
            'nb_organisations_actives',
            'nb_places_disponibles',
            'nb_places_offertes',
            'taux_occupation'
        ];
    }
}

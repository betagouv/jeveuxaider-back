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
                ->whereIn('state', ['Validée','Terminée'])
                ->get();

            $places_available_left = $missionsAvailableCollection->sum('places_left');
            $places_offered = $missionsAvailableCollection->sum('participations_max');
            $total_participations_max = $missionsCollection->sum('participations_max');
            $datas->push([
                'id' => $collectivity->id,
                'name' => $collectivity->name,
                'published' => $collectivity->published,
                'missions_count' => Mission::whereIn('zip', $collectivity->zips)->count(),
                'missions_validated_count' => Mission::whereIn('zip', $collectivity->zips)->where('state', 'Validée')->count(),
                'missions_refused_count' => Mission::whereIn('zip', $collectivity->zips)->where('state', 'Refusée')->count(),
                'missions_finished_count' => Mission::whereIn('zip', $collectivity->zips)->where('state', 'Terminée')->count(),
                'structures_count' => Structure::whereIn('zip', $collectivity->zips)->count(),
                'participations_count' => Participation::whereHas('mission', function (Builder $query) use ($collectivity) {
                    $query->whereIn('zip', $collectivity->zips);
                })->count(),
                'volontaires_count' => Profile::whereIn('zip', $collectivity->zips)->count(),
                'service_civique_count' => Profile::whereIn('zip', $collectivity->zips)
                    ->whereHas('user', function (Builder $query) {
                        $query->where('service_civique', true);
                    })->count(),
                    'missions_available' => $missionsAvailableCollection->count(),
                    'organisations_active' => $missionsAvailableCollection->pluck('structure_id')->unique()->count(),
                    'places_available' => $missionsAvailableCollection->sum('places_left'),
                    'total_offered_places' => $total_participations_max,
                    'occupation_rate' => $places_offered ? round(($places_available_left / $places_offered) * 100) : 0,
            ]);
        }

        return $datas;
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'published',
            'missions_count',
            'missions_validated_count',
            'missions_refused_count',
            'missions_finished_count',
            'structures_count',
            'participations_count',
            'volontaires_count',
            'service_civique_count',
            'missions_available',
            'organisations_active',
            'places_available',
            'total_offered_places',
            'occupation_rate'
        ];
    }
}

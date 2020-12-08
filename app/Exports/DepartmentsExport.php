<?php

namespace App\Exports;

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
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DepartmentsExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function collection()
    {
        $query = Collectivity::where('type', 'department')->where('state', 'validated');

        if ($this->role == 'referent') {
            $query->where('department', Auth::guard('api')->user()->profile->referent_department);
        }

        if ($this->role == 'referent_regional') {
            $query->whereIn('department', config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region]);
        }

        $collectivities = QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersCollectivitySearch),
            ])
            ->defaultSort('department')
            ->get();

        $stats = collect();

        foreach ($collectivities as $collectivity) {
            $missionsCollection = Mission::department($collectivity->department)
                ->hasPlacesLeft()
                ->available()
                ->get();

            // $places_left = $missionsCollection->sum('places_left');
            // $participations_max = $missionsCollection->sum('participations_max');
            $stats->push([
                'key' => $collectivity->department,
                'name' => $collectivity->name,
                'published' => $collectivity->published,
                'missions_count' => Mission::role($this->role)->department($collectivity->department)->count(),
                'structures_count' => Structure::role($this->role)->department($collectivity->department)->count(),
                'participations_count' => Participation::role($this->role)->department($collectivity->department)->count(),
                'volontaires_count' => Profile::role($this->role)
                    ->department($collectivity->department)
                    ->whereHas('user', function (Builder $query) {
                        $query->where('context_role', 'volontaire');
                    })
                    ->count(),
                'service_civique_count' => Profile::role($this->role)
                    ->department($collectivity->department)
                    ->whereHas('user', function (Builder $query) {
                        $query->where('service_civique', true);
                    })->count(),
                'missions_available' => $missionsCollection->count(),
                'organisations_active' => $missionsCollection->pluck('structure_id')->unique()->count(),
                'places_available' => $missionsCollection->sum('places_left'),
                // 'places_available' => $places_left,
                // 'places' => $participations_max,
                // 'taux_occupation' => $participations_max ? round((($participations_max - $places_left) / $participations_max) * 100) : 0
            ]);
        }

        return $stats;
    }

    public function headings(): array
    {
        return [
            'numero',
            'name',
            'published',
            'missions_count',
            'structures_count',
            'participations_count',
            'volontaires_count',
            'service_civique_count',
            'missions_available',
            'organisations_active',
            'places_available',
        ];
    }
}

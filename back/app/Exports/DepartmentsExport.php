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

        $departements = QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersCollectivitySearch),
            ])
            ->defaultSort('department')
            ->get();

        $stats = collect();

        foreach ($departements as $departement) {
            $missionsAvailableCollection = Mission::department($departement->department)
                ->hasPlacesLeft()
                ->available()
                ->get();

            $missionsCollection = Mission::department($departement->department)
                ->whereIn('state', ['Validée', 'Terminée'])
                ->get();

            $places_available_left = $missionsCollection->where('state', 'Validée')->sum('places_left');
            $places_offered = $missionsCollection->where('state', 'Validée')->sum('participations_max');
            $total_participations_max = $missionsCollection->sum('participations_max');

            $stats->push([
                'numero' => $departement->department,
                'nom' => $departement->name,
                'publie' => $departement->published,
                'nb_missions' => Mission::role($this->role)->department($departement->department)->count(),
                'nb_structures' => Structure::role($this->role)->department($departement->department)->count(),
                'nb_participations' => Participation::role($this->role)->department($departement->department)->count(),
                'nb_volontaires' => Profile::role($this->role)
                    ->department($departement->department)
                    ->whereHas('user', function (Builder $query) {
                        $query->where('context_role', 'volontaire');
                    })
                    ->count(),
                'nb_service_civique' => Profile::role($this->role)
                    ->department($departement->department)
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

        return $stats;
    }

    public function headings(): array
    {
        return [
            'numero',
            'nom',
            'publie',
            'nb_missions',
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

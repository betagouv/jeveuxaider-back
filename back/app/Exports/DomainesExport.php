<?php

namespace App\Exports;

use App\Filters\FiltersTagName;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Tag;
use Maatwebsite\Excel\Concerns\Exportable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;

class DomainesExport implements FromCollection, WithHeadings
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
        $domaines = QueryBuilder::for(Tag::where('type', 'domaine'))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersTagName),
            ])
            ->defaultSort('name->fr')
            ->get();

        $datas = collect();

        foreach ($domaines as $domaine) {
            $missionsAvailableCollection = Mission::role($this->role)
                ->domaine($domaine->id)
                ->hasPlacesLeft()
                ->available()
                ->get();

            $missionsCollection = Mission::role($this->role)
                ->domaine($domaine->id)
                ->whereIn('state', ['Validée','Terminée'])
                ->get();

            $places_available_left = $missionsCollection->where('state', 'Validée')->sum('places_left');
            $places_offered = $missionsCollection->where('state', 'Validée')->sum('participations_max');
            $total_participations_max = $missionsCollection->sum('participations_max');
            $datas->push([
                'id' => $domaine->id,
                'nom' => $domaine->name,
                'nb_missions' => Mission::role($this->role)->domaine($domaine->id)->count(),
                'nb_participations' => Participation::role($this->role)->domaine($domaine->id)->count(),
                'nb_volontaires' => Profile::role($this->role)->domaine($domaine->id)->count(),
                'nb_service_civique' => Profile::role($this->role)->domaine($domaine->id)
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
            'nb_missions',
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

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
                'key' => $domaine->id,
                'name' => $domaine->name,
                'missions_count' => Mission::role($this->role)->domaine($domaine->id)->count(),
                'participations_count' => Participation::role($this->role)->domaine($domaine->id)->count(),
                'volontaires_count' => Profile::role($this->role)->domaine($domaine->id)->count(),
                'service_civique_count' => Profile::role($this->role)->domaine($domaine->id)
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
            'missions_count',
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

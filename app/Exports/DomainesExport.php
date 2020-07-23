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
            $missionsAvailableCollection = Mission::role($this->role)->available()->domaine($domaine->id)->get();
            $places_left = $missionsAvailableCollection->sum('places_left');
            $participations_max = $missionsAvailableCollection->sum('participations_max');
            $datas->push([
                'key' => $domaine->id,
                'name' => $domaine->name,
                'missions_count' => Mission::role($this->role)->domaine($domaine->id)->count(),
                'participations_count' => Participation::role($this->role)->domaine($domaine->id)->count(),
                'volontaires_count' => Profile::role($this->role)->domaine($domaine->id)->count(),
                'missions_available' => $missionsAvailableCollection->count(),
                'places_available' => $places_left,
                'places' => $participations_max,
                'taux_occupation' => $participations_max ? round((($participations_max - $places_left) / $participations_max) * 100) : 0
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
            'missions_available',
            'places_available',
            'places',
            'taux_occupation',
        ];
    }
}

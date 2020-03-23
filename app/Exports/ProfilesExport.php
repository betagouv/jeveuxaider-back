<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\Models\Profile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileRole;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfilesExport implements FromCollection, WithMapping, WithHeadings
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return QueryBuilder::for(Profile::role($this->request->header('Context-Role')))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('role', new FiltersProfileRole),
                'referent_department'
            )
            ->defaultSort('-updated_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'full_name',
            'first_name',
            'last_name',
            'email',
            'phone',
            'mobile',
            'avatar',
            'referent_department',
            'superviseur_reseau',
            'reseau_id',
            'admin',
            'referent',
            'superviseur',
            'responsable',
            'tuteur',
            'created_at',
            'updated_at',
            'structures',
            'registered',
            'volontaire'
        ];
    }

    public function map($profile): array
    {
        return [
            'id' => $profile->id,
            'full_name' => $profile->full_name,
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'email' => $profile->email,
            'phone' => $profile->phone,
            'mobile' => $profile->mobile,
            'avatar' => $profile->avatar,
            'referent_department' => $profile->referent_department,
            'superviseur_reseau' => $profile->superviseur_reseau ? $profile->superviseur_reseau['name'] : '',
            'reseau_id' => $profile->reseau_id,
            'admin' => $profile->isAdmin(),
            'referent' => $profile->isReferent(),
            'superviseur' => $profile->isSuperviseur(),
            'responsable' => $profile->isResponsable(),
            'tuteur' => $profile->isTuteur(),
            'created_at' => $profile->created_at,
            'updated_at' => $profile->updated_at,
            'structures' => $profile->structures ? $profile->structures->pluck('name')->implode(', ') : '',
            'registered' => $profile->has_user ? true : false,
            'volontaire' => $profile->volontaire,
        ];
    }
}

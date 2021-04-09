<?php

namespace App\Exports;

use App\Models\Profile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileRole;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfilesResponsablesExport implements FromCollection, WithMapping, WithHeadings
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return QueryBuilder::for(Profile::class)
            ->allowedAppends('roles', 'has_user', 'responsable_waiting_actions')
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('role', new FiltersProfileRole),
            )
            ->defaultSort('-created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'nom_complet',
            'prenom',
            'nom',
            'email',
            'telephone',
            'mobile',
            'code_postal',
            'structure',
            'nb_actions_en_attente',
            'date_creation',
            'date_modification',
            'derniere_connexion',
        ];
    }

    public function map($profile): array
    {
        return [
            $profile->id,
            $profile->full_name,
            $profile->first_name,
            $profile->last_name,
            $profile->email,
            $profile->phone,
            $profile->mobile,
            $profile->zip,
            $profile->structures->first()->name,
            $profile->responsable_waiting_actions['total'],
            $profile->created_at,
            $profile->updated_at,
            $profile->last_online_at,
        ];
    }
}

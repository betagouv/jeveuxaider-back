<?php

namespace App\Exports;

use App\Models\Profile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileRole;
use App\Filters\FiltersProfileTag;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfilesReferentsDepartementsExport implements FromCollection, WithMapping, WithHeadings
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return QueryBuilder::for(Profile::class)
            ->allowedAppends('roles', 'has_user', 'skills', 'domaines', 'referent_waiting_actions')
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('role', new FiltersProfileRole),
                AllowedFilter::custom('domaines', new FiltersProfileTag),
                AllowedFilter::exact('is_visible'),
                'referent_department'
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
            'referent_departement',
            'nb_organisations_en_attente',
            'nb_missions_en_attente',
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
            $profile->referent_department,
            $profile->referent_waiting_actions['structures'],
            $profile->referent_waiting_actions['missions'],
            $profile->referent_waiting_actions['total'],
            $profile->created_at,
            $profile->updated_at,
            $profile->last_online_at,
        ];
    }
}

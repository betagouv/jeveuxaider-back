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

class ProfilesTetesDeReseauExport implements FromCollection, WithMapping, WithHeadings
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return QueryBuilder::for(Profile::class)
            ->allowedAppends('roles', 'has_user', 'skills', 'domaines', 'tete_de_reseau_waiting_actions')
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('role', new FiltersProfileRole),
                AllowedFilter::custom('domaines', new FiltersProfileTag),
                AllowedFilter::exact('is_visible'),
                'referent_region'
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
            'reseau_id',
            'reseau_name',
            'nb_antennes',
            'nb_missions_en_attente',
            'nb_participations_en_attente',
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
            $profile->tete_de_reseau_id,
            $profile->teteDeReseau->name,
            $profile->tete_de_reseau_waiting_actions['antennes'],
            $profile->tete_de_reseau_waiting_actions['missions'],
            $profile->tete_de_reseau_waiting_actions['participations'],
            $profile->tete_de_reseau_waiting_actions['total'],
            $profile->created_at,
            $profile->updated_at,
            $profile->last_online_at,
        ];
    }
}

<?php

namespace App\Exports;

use App\Filters\FiltersProfileCollectivity;
use App\Filters\FiltersProfilePostalCode;
use App\Models\Profile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileRole;
use App\Filters\FiltersProfileTag;
use App\Filters\FiltersProfileZips;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;

class ProfilesExport implements FromCollection, WithMapping, WithHeadings, ShouldQueue
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
        return QueryBuilder::for(Profile::role($this->role))
            ->allowedAppends('roles', 'has_user', 'skills', 'domaines')
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch),
                AllowedFilter::custom('role', new FiltersProfileRole),
                AllowedFilter::custom('domaines', new FiltersProfileTag),
                AllowedFilter::custom('zips', new FiltersProfileZips),
                AllowedFilter::custom('postal_code', new FiltersProfilePostalCode),
                AllowedFilter::custom('collectivity', new FiltersProfileCollectivity),
                AllowedFilter::exact('is_visible'),
                AllowedFilter::exact('referent_department'),
                'referent_region'
            )
            ->defaultSort('-created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'user_id',
            'full_name',
            'first_name',
            'last_name',
            'email',
            'phone',
            'mobile',
            'zip',
            'referent_department',
            'referent_region',
            'reseau_id',
            'service_civique',
            'is_visible',
            'created_at',
            'updated_at',
        ];
    }

    public function map($profile): array
    {
        return [
            $profile->id,
            $profile->user->id ?? null,
            $profile->full_name,
            $profile->first_name,
            $profile->last_name,
            $profile->email,
            $profile->phone,
            $profile->mobile,
            $profile->zip,
            $profile->referent_department,
            $profile->referent_region,
            $profile->reseau_id,
            $profile->service_civique,
            $profile->is_visible,
            $profile->created_at,
            $profile->updated_at,
        ];
    }
}

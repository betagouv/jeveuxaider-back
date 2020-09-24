<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\Models\Profile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersProfileSearch;
use App\Filters\FiltersProfileRole;
use App\Filters\FiltersProfileTag;
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
            ->allowedAppends('roles', 'has_user', 'skills', 'domaines')
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
            $profile->user->id,
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

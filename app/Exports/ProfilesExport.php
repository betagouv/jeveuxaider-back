<?php

namespace App\Exports;

use App\Filters\FiltersProfileMinParticipations;
use App\Filters\FiltersProfileSearch;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\UserHasExportedDatas;
use App\Sorts\ProfileParticipationsValidatedCountSort;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Concerns\WithEvents;

class ProfilesExport implements FromQuery, WithMapping, WithHeadings, WithEvents
{
    use Exportable;
    use RegistersEventListeners;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function afterSheet(AfterSheet $event)
    {
        $nbRows = $event->sheet->getHighestRow() > 0 ? $event->sheet->getHighestRow() - 1 : 0;

        activity('export')
            ->event('exported')
            ->withProperties([
                'type' => 'utilisateurs',
                'items_count' => $nbRows,
                'filter' => request()->input('filter')
            ])
            ->log("exported");

        $currentUser = User::find(Auth::guard('api')->user()->id);

        Notification::route('slack', config('services.slack.hook_url'))
            ->notify(new UserHasExportedDatas($currentUser, 'utilisateurs', $nbRows));
    }

    public function query()
    {
        if ($this->isResponsableExport()) {
            $queryBuilder = Profile::role($this->request->header('Context-Role'))->with(['structures']);
        } else {
            $queryBuilder = Profile::role($this->request->header('Context-Role'));
        }

        return QueryBuilder::for($queryBuilder)
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersProfileSearch()),
                AllowedFilter::scope('department'),
                AllowedFilter::scope('user.role'),
                AllowedFilter::exact('zip'),
                AllowedFilter::exact('is_visible'),
                AllowedFilter::custom('min_participations', new FiltersProfileMinParticipations())
            )
            ->defaultSort('-created_at')
            ->allowedSorts([
                'created_at',
                AllowedSort::custom('participations_validated_count', new ProfileParticipationsValidatedCountSort()),
            ]);
    }

    public function headings(): array
    {
        if ($this->isResponsableExport()) {
            return [
                'id',
                'prenom',
                'nom',
                'email',
                'phone',
                'mobile',
                'code_postal',
                'structure_id',
                'structure_nom',
            ];
        }

        return [
            'id',
            'prenom',
            'email',
            'code_postal',
        ];
    }

    public function map($profile): array
    {
        if ($this->isResponsableExport()) {
            $structure = $profile->user->structures->first();

            return [
                $profile->id,
                $profile->first_name,
                $profile->last_name,
                $profile->email,
                $profile->phone,
                $profile->mobile,
                $profile->zip,
                $structure ? $structure->id : null,
                $structure ? $structure->name : null,
            ];
        }

        return [
            $profile->id,
            $profile->first_name,
            $profile->email,
            $profile->zip,
        ];
    }

    private function isResponsableExport(): bool
    {
        return $this->request->has('filter.role') && $this->request->input('filter.role') == 'responsable';
    }
}

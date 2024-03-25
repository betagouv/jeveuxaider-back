<?php

namespace App\Exports;

use App\Filters\FiltersParticipationNeedToBeTreated;
use App\Filters\FiltersParticipationSearch;
use App\Models\Participation;
use App\Models\User;
use App\Notifications\UserHasExportedDatas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ParticipationsExport implements FromQuery, WithMapping, WithHeadings, WithEvents
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
                'type' => 'participations',
                'items_count' => $nbRows,
                'filter' => request()->input('filter')
            ])
            ->log("exported");

        $currentUser = User::find(Auth::guard('api')->user()->id);

        Notification::route('slack', config('services.slack.hook_url'))
            ->notify(new UserHasExportedDatas($currentUser, 'participations', $nbRows));
    }

    public function query()
    {

        $contextRole = $this->request->header('Context-Role');

        $queryBuilder = Participation::role($contextRole)
            ->when($contextRole != 'admin', function ($q) {
                $q->whereIn('state', ['Validée', 'En attente de validation', 'En cours de traitement']);
            })
            ->with(['profile', 'mission']);

        return QueryBuilder::for($queryBuilder)
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationSearch()),
                AllowedFilter::custom('need_to_be_treated', new FiltersParticipationNeedToBeTreated()),
                AllowedFilter::exact('mission.id'),
                AllowedFilter::exact('mission.name'),
                AllowedFilter::exact('mission.department'),
                AllowedFilter::exact('mission.structure.name'),
                AllowedFilter::exact('mission.structure.id'),
                AllowedFilter::exact('mission.template.id'),
                AllowedFilter::exact('profile.id'),
                AllowedFilter::scope('ofReseau'),
                AllowedFilter::scope('ofTerritoire'),
                AllowedFilter::scope('ofActivity'),
                AllowedFilter::scope('ofDomaine'),
                AllowedFilter::scope('ofResponsable'),
                AllowedFilter::exact('state'),
                AllowedFilter::exact('mission.zip'),
                AllowedFilter::exact('mission.type'),
                AllowedFilter::exact('id'),
            )
            ->defaultSort('-created_at');
    }

    public function headings(): array
    {
        return [
            'id',
            'statut',
            'mission_id',
            'mission_nom',
            'mission_date_debut',
            'mission_date_fin',
            'profile_id',
            'benevole_prenom',
            'benevole_nom',
            'benevole_mobile',
            'benevole_email',
            'benevole_code_postal',
            'benevole_date_anniversaire',
            'créneaux_bénévole',
            'date_creation',
            'date_modification',
        ];
    }

    public function map($participation): array
    {
        $hidden = ($participation->mission && $participation->mission->state == 'Signalée') && $this->request->header('Context-Role') == 'responsable'
            ? true : false;

        $creneaux = null;

        if($participation->slots) {
            $creneaux = implode(', ', collect($participation->slots)->map( function ($item) {
                $date = Carbon::parse($item['date'])->timezone('Europe/Paris')->format('Y-m-d');
                $slots = implode(', ', $item['slots']);
                return "$date ($slots)";
            })->toArray());
        }

        return [
            $participation->id,
            $participation->state,
            $participation->mission_id,
            $participation->mission ? $participation->mission->name : '',
            $participation->mission ? $participation->mission->start_date : '',
            $participation->mission ? $participation->mission->end_date : '',
            $participation->profile_id,
            $participation->profile && !$hidden ? $participation->profile->first_name : '',
            $participation->profile && !$hidden ? $participation->profile->last_name : '',
            $participation->profile && !$hidden ? $participation->profile->mobile : '',
            $participation->profile && !$hidden ? $participation->profile->email : '',
            $participation->profile && !$hidden ? $participation->profile->zip : '',
            $participation->profile && !$hidden ? Carbon::parse($participation->profile->birthday)->format('Y-m-d') : '',
            $creneaux,
            $participation->created_at,
            $participation->updated_at,
        ];
    }
}

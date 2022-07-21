<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopitoController extends Controller
{
    public $year;

    public $month;

    public $date;

    public $startDate;

    public $endDate;

    public function __construct(Request $request)
    {
        if ($request->input('daterange') == 'last-30-days') {
            $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->format('Y-m-d H:i:s');
        }
        if ($request->input('daterange') == 'last-7-days') {
            $this->startDate = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->format('Y-m-d H:i:s');
        }
        if ($request->input('daterange') == 'current-month') {
            $this->date = Carbon::now();
            $this->startDate = $this->date->startOfMonth()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfMonth()->format('Y-m-d H:i:s');
        }
        if ($request->input('daterange') == 'last-month') {
            $this->date = Carbon::now()->subMonth(1);
            $this->startDate = $this->date->startOfMonth()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfMonth()->format('Y-m-d H:i:s');
        }
        if ($request->input('daterange') == 'current-year') {
            $this->year = date('Y');
            $this->date = Carbon::parse($this->year.'-01-01');
            $this->startDate = $this->date->startOfYear()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfYear()->format('Y-m-d H:i:s');
        }
        if ($request->input('daterange') == 'last-year') {
            $this->year = date('Y') - 1;
            $this->date = Carbon::parse($this->year.'-01-01');
            $this->startDate = $this->date->startOfYear()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfYear()->format('Y-m-d H:i:s');
        }
        if ($request->input('daterange') == 'all') {
            $this->startDate = Carbon::create(2000, 01, 01, 0, 0, 0)->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->format('Y-m-d H:i:s');
        }
    }

    public function marketplace(Request $request)
    {
        $department = $request->input('department');

        // ALL
        if (! $department) {
            $results = DB::select('
                SELECT notifications_benevoles.profile_id, COUNT(*) AS count FROM notifications_benevoles
                WHERE notifications_benevoles.created_at BETWEEN :start and :end
                GROUP BY notifications_benevoles.profile_id
                ORDER BY count
                DESC LIMIT 5
            ', [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        } else {
            $results = DB::select("
                SELECT notifications_benevoles.profile_id, COUNT(*) AS count FROM notifications_benevoles
                LEFT JOIN profiles ON profiles.id = notifications_benevoles.profile_id
                WHERE profiles.department = '$department'
                AND notifications_benevoles.created_at BETWEEN :start and :end
                GROUP BY notifications_benevoles.profile_id
                ORDER BY count
                DESC LIMIT 5
            ", [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        }

        $ids = collect($results)->pluck('profile_id');
        $items = Profile::whereIn('id', $ids)->get();
        $items->map(function ($item) use ($results) {
            $item->count = collect($results)->filter(function ($values) use ($item) {
                return $values->profile_id == $item->id;
            })->first()->count;

            return $item;
        });

        $sortedItems = $items->sortByDesc('count');

        return [
            'items' => $sortedItems->values()->all(),
        ];
    }

    public function participations(Request $request)
    {
        $state = $request->input('state');

        // ALL
        if (! $state) {
            $results = DB::select('
                SELECT participations.profile_id, COUNT(*) AS count FROM participations
                WHERE participations.deleted_at IS NULL
                AND participations.created_at BETWEEN :start and :end
                GROUP BY participations.profile_id
                ORDER BY count
                DESC LIMIT 5
            ', [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        } else {
            $results = DB::select("
                SELECT participations.profile_id, COUNT(*) AS count FROM participations
                WHERE participations.deleted_at IS NULL
                AND participations.state = '$state'
                AND participations.created_at BETWEEN :start and :end
                GROUP BY participations.profile_id
                ORDER BY count
                DESC LIMIT 5
            ", [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        }

        $ids = collect($results)->pluck('profile_id');
        $items = Profile::whereIn('id', $ids)->get();
        $items->map(function ($item) use ($results) {
            $item->count = collect($results)->filter(function ($values) use ($item) {
                return $values->profile_id == $item->id;
            })->first()->count;

            return $item;
        });

        $sortedItems = $items->sortByDesc('count');

        return [
            'items' => $sortedItems->values()->all(),
        ];
    }

    public function utilisateursLesPlusActifs(Request $request)
    {
        $role = $request->input('role');

        if (! $role) {
            $results = DB::select("
                SELECT activity_log.causer_id, COUNT(*) AS count FROM activity_log
                WHERE activity_log.causer_type = 'App\Models\User'
                AND activity_log.created_at BETWEEN :start and :end
                GROUP BY activity_log.causer_id
                ORDER BY count
                DESC LIMIT 5
            ", [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        } elseif ($role == 'referent_department') {
            $results = DB::select("
                SELECT activity_log.causer_id, COUNT(*) AS count FROM activity_log
                LEFT JOIN profiles ON profiles.user_id = activity_log.causer_id
                WHERE activity_log.causer_type = 'App\Models\User'
                AND activity_log.created_at BETWEEN :start and :end
                AND profiles.referent_department IS NOT NULL
                GROUP BY activity_log.causer_id
                ORDER BY count
                DESC LIMIT 5
            ", [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        } elseif ($role == 'referent_region') {
            $results = DB::select("
                SELECT activity_log.causer_id, COUNT(*) AS count FROM activity_log
                LEFT JOIN profiles ON profiles.user_id = activity_log.causer_id
                WHERE activity_log.causer_type = 'App\Models\User'
                AND activity_log.created_at BETWEEN :start and :end
                AND profiles.referent_region IS NOT NULL
                GROUP BY activity_log.causer_id
                ORDER BY count
                DESC LIMIT 5
            ", [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        } elseif ($role == 'admin') {
            $results = DB::select("
                SELECT activity_log.causer_id, COUNT(*) AS count FROM activity_log
                LEFT JOIN users ON users.id = activity_log.causer_id
                WHERE activity_log.causer_type = 'App\Models\User'
                AND activity_log.created_at BETWEEN :start and :end
                AND users.is_admin = true
                GROUP BY activity_log.causer_id
                ORDER BY count
                DESC LIMIT 5
            ", [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        }

        $ids = collect($results)->pluck('causer_id');

        $items = User::whereIn('id', $ids)->get();
        $items->map(function ($item) use ($results) {
            $item->count = collect($results)->filter(function ($values) use ($item) {
                return $values->causer_id == $item->id;
            })->first()->count;

            return $item;
        });

        $sortedItems = $items->sortByDesc('count');

        return [
            'items' => $sortedItems->values()->all(),
        ];
    }

    public function organisationsMissions(Request $request)
    {
        $type = $request->input('type');

        // ALL
        if (! $type) {
            $results = DB::select('
                SELECT missions.structure_id, COUNT(*) AS count FROM missions
                WHERE missions.deleted_at IS NULL
                AND missions.created_at BETWEEN :start and :end
                GROUP BY missions.structure_id
                ORDER BY count
                DESC LIMIT 5
            ', [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        } else {
            $results = DB::select("
                SELECT missions.structure_id, COUNT(*) AS count FROM missions
                LEFT JOIN structures ON structures.id = missions.structure_id
                WHERE missions.deleted_at IS NULL
                AND structures.statut_juridique = '$type'
                AND missions.created_at BETWEEN :start and :end
                GROUP BY missions.structure_id
                ORDER BY count
                DESC LIMIT 5
            ", [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        }

        $ids = collect($results)->pluck('structure_id');
        $items = Structure::whereIn('id', $ids)->get();
        $items->map(function ($item) use ($results) {
            $item->count = collect($results)->filter(function ($values) use ($item) {
                return $values->structure_id == $item->id;
            })->first()->count;

            return $item;
        });

        $sortedItems = $items->sortByDesc('count');

        return [
            'items' => $sortedItems->values()->all(),
        ];
    }

    public function organisationsParticipations(Request $request)
    {
        $type = $request->input('type');

        // ALL
        if (! $type) {
            $results = DB::select('
                SELECT COUNT(*) AS count, missions.structure_id FROM participations
                LEFT JOIN missions ON participations.mission_id = missions.id
                LEFT JOIN structures ON missions.structure_id = structures.id
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND participations.created_at BETWEEN :start and :end
                GROUP BY missions.structure_id
                ORDER BY count
                DESC LIMIT 5
            ', [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        } else {
            $results = DB::select("
                SELECT COUNT(*) AS count, missions.structure_id FROM participations
                LEFT JOIN missions ON participations.mission_id = missions.id
                LEFT JOIN structures ON missions.structure_id = structures.id
                WHERE participations.deleted_at IS NULL
                AND structures.statut_juridique = '$type'
                AND missions.deleted_at IS NULL
                AND participations.created_at BETWEEN :start and :end
                GROUP BY missions.structure_id
                ORDER BY count
                DESC LIMIT 5
            ", [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]);
        }

        $ids = collect($results)->pluck('structure_id');
        $items = Structure::whereIn('id', $ids)->get();
        $items->map(function ($item) use ($results) {
            $item->count = collect($results)->filter(function ($values) use ($item) {
                return $values->structure_id == $item->id;
            })->first()->count;

            return $item;
        });

        $sortedItems = $items->sortByDesc('count');

        return [
            'items' => $sortedItems->values()->all(),
        ];
    }
}

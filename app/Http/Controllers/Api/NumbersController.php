<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Mission;
use App\Models\NotificationBenevole;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Reseau;
use App\Models\Structure;
use App\Models\Territoire;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NumbersController extends Controller
{
    public $year;

    public $month;

    public $startDate;

    public $endDate;

    public $department;

    public function __construct(Request $request)
    {
        if ($request->input('period') == 'current_year') {
            $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->endOfYear()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'last_year') {
            $this->startDate = Carbon::now()->subYear(1)->startOfYear()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->subYear(1)->endOfYear()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'current_month') {
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'last_month') {
            $this->startDate = Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'current_week') {
            $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'last_week') {
            $this->startDate = Carbon::now()->subWeek(1)->startOfWeek()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->subWeek(1)->endOfWeek()->format('Y-m-d H:i:s');
        } else {
            $this->startDate = Carbon::create(2000, 01, 01, 0, 0, 0)->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->format('Y-m-d H:i:s');
        }

        if ($request->has('department')) {
            $this->department = $request->input('department');
        }
    }

    public function overviewQuickGlance(Request $request)
    {
        return [
            'organisations' => Structure::whereIn('state', ['Validée'])->whereBetween('created_at', [$this->startDate, $this->endDate])->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->count(),
            'missions' => Mission::whereIn('state', ['Validée', 'Terminée'])->whereBetween('created_at', [$this->startDate, $this->endDate])->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->count(),
            'participations' => Participation::whereIn('state', ['Validée'])->whereBetween('created_at', [$this->startDate, $this->endDate])->when($this->department, function ($query) {
                $query->department($this->department);
            })->count(),
            'utilisateurs' => Profile::whereBetween('created_at', [$this->startDate, $this->endDate])->when($this->department, function ($query) {
                $query->department($this->department);
            })->count(),
        ];
    }

    public function overviewOrganisations(Request $request)
    {
        $missionsAvailable = Mission::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->available()
            ->get();

        return [
            'organisations' => Structure::when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->count(),
            'organisations_actives' => $missionsAvailable->pluck('structure_id')->unique()->count(),
            'reseaux' => Reseau::where('is_published', true)->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->count(),
            'territoires' => Territoire::where('is_published', true)->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->count(),
        ];
    }

    public function overviewMissions(Request $request)
    {
        $missionsAvailable = Mission::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->available()
            ->get();

        $placesLeft = $missionsAvailable->sum('places_left');
        $placesOffered = $missionsAvailable->sum('participations_max');

        return [
            'missions_available' => $missionsAvailable->count(),
            'places' => $placesOffered,
            'places_left' => $placesLeft,
            'places_occupation_rate' => $placesOffered ? round((($placesOffered - $placesLeft) / $placesOffered) * 100) : 0,
        ];
    }

    public function overviewUtilisateurs(Request $request)
    {
        return [
            'utilisateurs' => Profile::role($request->header('Context-Role'))->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->count(),
            'benevoles' => Profile::role($request->header('Context-Role'))->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereHas('user', function (Builder $query) {
                $query->where('context_role', 'volontaire');
            })->count(),
            'benevoles_actifs' => Profile::role($request->header('Context-Role'))->has('participations')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->count(),
            'participations_validated' => Participation::role($request->header('Context-Role'))->where('state', 'Validée')->when($this->department, function ($query) {
                $query->whereHas('profile', function (Builder $query) {
                    $query->department($this->department);
                });
            })->count(),
        ];
    }

    public function globalOrganisations(Request $request)
    {
        $organisationsCount = Structure::role($request->header('Context-Role'))->when($this->department, function ($query) {
            $query->where('department', $this->department);
        })->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        $organisationsValidatedCount = Structure::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->when($this->department, function ($query) {
            $query->where('department', $this->department);
        })->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        return [
            'organisations_count' => $organisationsCount,
            'organisations_validated_count' => $organisationsValidatedCount,
            'organisations_conversion_rate' => $organisationsCount ? round(($organisationsValidatedCount / $organisationsCount) * 100) : 0,
            'organisations_response_time_avg' => round(Structure::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->avg('response_time')),
            'organisations_response_ratio_avg' => round(Structure::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->avg('response_ratio')),
        ];
    }

    public function organisationsByStates(Request $request)
    {
        return [
            'draft' => Structure::role($request->header('Context-Role'))->where('state', 'Brouillon')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'waiting' => Structure::role($request->header('Context-Role'))->where('state', 'En attente de validation')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'in_progress' => Structure::role($request->header('Context-Role'))->where('state', 'En cours de traitement')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'validated' => Structure::role($request->header('Context-Role'))->where('state', 'Validée')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'signaled' => Structure::role($request->header('Context-Role'))->where('state', 'Signalée')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'unsubscribed' => Structure::role($request->header('Context-Role'))->where('state', 'Désinscrite')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function organisationsByTypes(Request $request)
    {
        return [
            'associations' => Structure::role($request->header('Context-Role'))->where('state', 'Validée')->where('statut_juridique', 'Association')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'collectivites' => Structure::role($request->header('Context-Role'))->where('state', 'Validée')->where('statut_juridique', 'Collectivité')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'organisations_publiques' => Structure::role($request->header('Context-Role'))->where('state', 'Validée')->where('statut_juridique', 'Organisation publique')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'organisations_privees' => Structure::role($request->header('Context-Role'))->where('state', 'Validée')->where('statut_juridique', 'Organisation privée')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function organisationsByReseaux(Request $request)
    {
        $results = DB::select('
                SELECT reseaux.name, reseaux.id, COUNT(*) AS count FROM structures
                LEFT JOIN reseau_structure ON reseau_structure.structure_id = structures.id
                LEFT JOIN reseaux ON reseaux.id = reseau_structure.reseau_id
                WHERE structures.deleted_at IS NULL
                AND structures.department ILIKE :department
                AND reseaux.name IS NOT NULL
                AND structures.created_at BETWEEN :start and :end
                GROUP BY reseaux.name, reseaux.id
                ORDER BY count DESC
                LIMIT 5
            ', [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function missionsByReseaux(Request $request)
    {
        $results = DB::select("
                SELECT reseaux.name, reseaux.id, COUNT(*) AS count,
                SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END) AS sum_places_left
                FROM missions
                LEFT JOIN structures ON structures.id = missions.structure_id
                LEFT JOIN reseau_structure ON reseau_structure.structure_id = structures.id
                LEFT JOIN reseaux ON reseaux.id = reseau_structure.reseau_id
                WHERE structures.deleted_at IS NULL
                AND missions.department ILIKE :department
                AND structures.state = 'Validée'
                AND structures.created_at BETWEEN :start and :end
                AND missions.deleted_at IS NULL
                AND missions.state IN ('Validée', 'Terminée')
                AND reseaux.name IS NOT NULL
                GROUP BY reseaux.name, reseaux.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByReseaux(Request $request)
    {
        $results = DB::select('
                SELECT reseaux.name, reseaux.id, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN structures ON structures.id = missions.structure_id
                LEFT JOIN reseau_structure ON reseau_structure.structure_id = structures.id
                LEFT JOIN reseaux ON reseaux.id = reseau_structure.reseau_id
                WHERE structures.deleted_at IS NULL
                AND missions.department ILIKE :department
                AND missions.deleted_at IS NULL
                AND reseaux.name IS NOT NULL
                AND structures.created_at BETWEEN :start and :end
                GROUP BY reseaux.name, reseaux.id
                ORDER BY count DESC
                LIMIT 5
            ', [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function globalMissions(Request $request)
    {
        $missionsCount = Mission::role($request->header('Context-Role'))
        ->when($this->department, function ($query) {
            $query->where('department', $this->department);
        })
        ->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        $missionsValidatedOverCount = Mission::role($request->header('Context-Role'))->whereIn('state', ['Validée', 'Terminée'])
        ->when($this->department, function ($query) {
            $query->where('department', $this->department);
        })
        ->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        return [
            'missions' => $missionsCount,
            'missions_validated_and_over' => $missionsValidatedOverCount,
            'missions_conversion_rate' => $missionsCount ? round(($missionsValidatedOverCount / $missionsCount) * 100) : 0,
            'missions_participations_max_sum' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Validée', 'Terminée'])->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->sum('participations_max'),
            'missions_snu' => Mission::role($request->header('Context-Role'))->where('is_snu_mig_compatible', true)->whereIn('state', ['Validée', 'Terminée'])->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'missions_snu_participations_max_sum' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Validée', 'Terminée'])->where('is_snu_mig_compatible', true)->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->sum('snu_mig_places'),
        ];
    }

    public function globalParticipations(Request $request)
    {
        $participationsCount = Participation::role($request->header('Context-Role'))->when($this->department, function ($query) {
            $query->department($this->department);
        })
        ->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        $participationsValidatedCount = Participation::role($request->header('Context-Role'))->where('state', 'Validée')->when($this->department, function ($query) {
            $query->department($this->department);
        })->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        return [
            'participations' => $participationsCount,
            'participations_validated' => $participationsValidatedCount,
            'participations_conversion_rate' => $participationsCount ? round(($participationsValidatedCount / $participationsCount) * 100) : 0,
        ];
    }

    public function globalUtilisateurs(Request $request)
    {
        $usersWithParticipations = Profile::role($request->header('Context-Role'))->when($this->department, function ($query) {
            $query->department($this->department);
        })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->has('participations')->count();
        $participationsCount = Participation::role($request->header('Context-Role'))->when($this->department, function ($query) {
            $query->department($this->department);
        })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        return [
            'utilisateurs' => Profile::role($request->header('Context-Role'))->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'benevoles' => Profile::role($request->header('Context-Role'))->whereHas('user', function (Builder $query) {
                $query->where('context_role', 'volontaire');
            })->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'benevoles_actifs' => $usersWithParticipations,
            'benevoles_visibles_marketplace' => Profile::role($request->header('Context-Role'))->where('is_visible', true)->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'benevoles_notifications_martketplace' => NotificationBenevole::role($request->header('Context-Role'))->when($this->department, function ($query) {
                $query->whereHas('profile', function (Builder $query) {
                    $query->department($this->department);
                });
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'utilisateurs_with_participations' => $usersWithParticipations,
            'participations_avg' => $usersWithParticipations ? round($participationsCount / $usersWithParticipations, 1) : 0,
        ];
    }

    public function participationsByStates(Request $request)
    {
        return [
            'waiting' => Participation::role($request->header('Context-Role'))->where('state', 'En attente de validation')->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'in_progress' => Participation::role($request->header('Context-Role'))->where('state', 'En cours de traitement')->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'validated' => Participation::role($request->header('Context-Role'))->where('state', 'Validée')->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'refused' => Participation::role($request->header('Context-Role'))->where('state', 'Refusée')->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'canceled' => Participation::role($request->header('Context-Role'))->where('state', 'Annulée')->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function missionsByStates(Request $request)
    {
        return [
            'draft' => Mission::role($request->header('Context-Role'))->where('state', 'Brouillon')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'waiting' => Mission::role($request->header('Context-Role'))->where('state', 'En attente de validation')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'in_progress' => Mission::role($request->header('Context-Role'))->where('state', 'En cours de traitement')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'validated' => Mission::role($request->header('Context-Role'))->where('state', 'Validée')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'finished' => Mission::role($request->header('Context-Role'))->where('state', 'Terminée')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'canceled' => Mission::role($request->header('Context-Role'))->where('state', 'Annulée')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'signaled' => Mission::role($request->header('Context-Role'))->where('state', 'Signalée')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function missionsByTypes(Request $request)
    {
        return [
            'presentiels' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Validée', 'Terminée'])->where('type', 'Mission en présentiel')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'distances' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Validée', 'Terminée'])->where('type', 'Mission à distance')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function missionsByTemplateTypes(Request $request)
    {
        return [
            'with_template' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Validée', 'Terminée'])->whereNotNull('template_id')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'without_template' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Validée', 'Terminée'])->whereNull('template_id')->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function missionsByOrganisations(Request $request)
    {
        $results = DB::select("
                SELECT structures.name, structures.id, COUNT(*) AS count FROM missions
                LEFT JOIN structures ON structures.id = missions.structure_id
                WHERE missions.deleted_at IS NULL
                AND missions.department ILIKE :department
                AND missions.deleted_at IS NULL
                AND missions.state IN ('Validée', 'Terminée')
                AND structures.name IS NOT NULL
                AND missions.created_at BETWEEN :start and :end
                GROUP BY structures.name, structures.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByActivities(Request $request)
    {
        $results = DB::select("
                SELECT activities.name, activities.id, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN activities ON activities.id = mission_templates.activity_id OR activities.id = missions.activity_id
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND missions.department ILIKE :department
                AND participations.created_at BETWEEN :start and :end
                AND participations.state IN ('Validée')
                AND activities.name IS NOT NULL
                GROUP BY activities.name,activities.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByMissionTemplates(Request $request)
    {
        $results = DB::select('
                SELECT mission_templates.title, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND missions.department ILIKE :department
                AND mission_templates.title IS NOT NULL
                AND participations.created_at BETWEEN :start and :end
                GROUP BY mission_templates.title
                ORDER BY count DESC
                LIMIT 5
            ', [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByMissions(Request $request)
    {
        $results = DB::select('
                SELECT missions.id, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND missions.department ILIKE :department
                AND participations.created_at BETWEEN :start and :end
                GROUP BY missions.id
                ORDER BY count DESC
                LIMIT 5
            ', [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByOrganisations(Request $request)
    {
        $results = DB::select('
                SELECT structures.id, structures.name, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN structures ON structures.id = missions.structure_id
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND missions.department ILIKE :department
                AND structures.name IS NOT NULL
                AND participations.created_at BETWEEN :start and :end
                GROUP BY structures.id, structures.name
                ORDER BY count DESC
                LIMIT 5
            ', [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByDomaines(Request $request)
    {
        $results = DB::select("
                SELECT domaines.name, domaines.id, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN domaines ON domaines.id = mission_templates.domaine_id OR domaines.id = missions.domaine_id OR domaines.id = missions.domaine_secondary_id
                WHERE missions.deleted_at IS NULL
                AND participations.created_at BETWEEN :start and :end
                AND participations.state IN ('Validée')
                AND missions.department ILIKE :department
                GROUP BY domaines.name, domaines.id
                ORDER BY count DESC
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function missionsByActivities(Request $request)
    {
        $results = DB::select("
                SELECT activities.name, activities.id, COUNT(*) AS count FROM missions
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN activities ON activities.id = mission_templates.activity_id OR activities.id = missions.activity_id
                WHERE missions.deleted_at IS NULL
                AND missions.created_at BETWEEN :start and :end
                AND missions.state IN ('Validée', 'Terminée')
                AND missions.department ILIKE :department
                AND activities.name IS NOT NULL
                GROUP BY activities.name, activities.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function missionsByDomaines(Request $request)
    {
        $results = DB::select("
                SELECT domaines.name, domaines.id, COUNT(*) AS count
                FROM missions
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN domaines ON domaines.id = mission_templates.domaine_id OR domaines.id = missions.domaine_id OR domaines.id = missions.domaine_secondary_id
                WHERE missions.deleted_at IS NULL
                AND missions.state IN ('Validée', 'Terminée')
                AND missions.department ILIKE :department
                AND missions.created_at BETWEEN :start and :end
                GROUP BY domaines.name, domaines.id
                ORDER BY count DESC
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function missionsByTemplates(Request $request)
    {
        $results = DB::select("
                SELECT mission_templates.title, mission_templates.id, COUNT(*) AS count FROM missions
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                WHERE missions.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND missions.state IN ('Validée', 'Terminée')
                AND missions.department ILIKE :department
                AND mission_templates.title IS NOT NULL
                AND missions.created_at BETWEEN :start and :end
                GROUP BY mission_templates.title, mission_templates.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function organisationsByDomaines(Request $request)
    {
        $results = DB::select("
                SELECT domaines.name, domaines.id, COUNT(*) AS count FROM structures
                LEFT JOIN domainables ON domainables.domainable_id = structures.id AND domainables.domainable_type = 'App\Models\Structure'
                LEFT JOIN domaines ON domaines.id = domainables.domaine_id
                WHERE structures.deleted_at IS NULL
                AND structures.department ILIKE :department
                AND structures.state IN ('Validée')
                AND structures.created_at BETWEEN :start and :end
                GROUP BY domaines.name, domaines.id
                ORDER BY count DESC
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function utilisateursByDomaines(Request $request)
    {
        $results = DB::select("
                SELECT domaines.name, domaines.id, COUNT(*) AS count FROM profiles
                LEFT JOIN domainables ON domainables.domainable_id = profiles.id AND domainables.domainable_type = 'App\Models\Profile'
                LEFT JOIN domaines ON domaines.id = domainables.domaine_id
                WHERE profiles.created_at BETWEEN :start and :end
                AND profiles.zip ILIKE :department
                GROUP BY domaines.name, domaines.id
                ORDER BY count DESC
            ", [
            'department' => $this->department ? $this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function utilisateursWithParticipations(Request $request)
    {
        return [
            'with_participations' => Profile::role($request->header('Context-Role'))->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->has('participations')->count(),
            'without_participations' => Profile::role($request->header('Context-Role'))->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->doesntHave('participations')->count(),
        ];
    }

    public function missionsOutdatedByOrganisations(Request $request)
    {
        $results = DB::table('missions')
            ->select(['structures.name', 'structures.id'])
            ->selectRaw('count(*) as count')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->whereNotNull('missions.end_date')
            ->whereNull('missions.deleted_at')
            ->whereNull('structures.deleted_at')
            ->where('missions.end_date', '<', date('Y-m-d'))
            ->where('missions.state', 'Validée')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            // ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->orderByDesc('count')
            ->groupBy(['structures.name', 'structures.id'])
            ->limit(100)
            ->get();

        return $results;
    }

    public function participationsWaitingByOrganisations(Request $request)
    {
        $results = DB::select("
            SELECT structures.name, structures.id, COUNT(*) AS count FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN structures ON structures.id = missions.structure_id
            WHERE participations.deleted_at IS NULL
            AND participations.state = 'En attente de validation'
            AND missions.deleted_at IS NULL
            AND missions.department ILIKE :department
            AND structures.deleted_at IS NULL
            AND structures.name IS NOT NULL
            AND participations.created_at BETWEEN :start and :end
            GROUP BY structures.name, structures.id
            ORDER BY count DESC
            LIMIT 100
        ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function participationsRefusedByOrganisations(Request $request)
    {
        $results = DB::select("
            SELECT structures.name, structures.id, COUNT(*) AS count FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN structures ON structures.id = missions.structure_id
            WHERE participations.deleted_at IS NULL
            AND participations.state = 'Refusée'
            AND missions.deleted_at IS NULL
            AND missions.department ILIKE :department
            AND structures.deleted_at IS NULL
            AND structures.name IS NOT NULL
            AND participations.created_at BETWEEN :start and :end
            GROUP BY structures.name, structures.id
            ORDER BY count DESC
            LIMIT 100
        ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function participationsCanceledByOrganisations(Request $request)
    {
        $results = DB::select("
            SELECT structures.name, structures.id, COUNT(*) AS count FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN structures ON structures.id = missions.structure_id
            WHERE participations.deleted_at IS NULL
            AND participations.state = 'Annulée'
            AND missions.deleted_at IS NULL
            AND missions.department ILIKE :department
            AND structures.deleted_at IS NULL
            AND structures.name IS NOT NULL
            AND participations.created_at BETWEEN :start and :end
            GROUP BY structures.name, structures.id
            ORDER BY count DESC
            LIMIT 100
        ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function participationsInProgressByOrganisations(Request $request)
    {
        $results = DB::select("
            SELECT structures.name, structures.id, COUNT(*) AS count FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN structures ON structures.id = missions.structure_id
            WHERE participations.deleted_at IS NULL
            AND participations.state = 'En cours de traitement'
            AND missions.deleted_at IS NULL
            AND missions.department ILIKE :department
            AND structures.deleted_at IS NULL
            AND structures.name IS NOT NULL
            AND participations.created_at BETWEEN :start and :end
            GROUP BY structures.name, structures.id
            ORDER BY count DESC
            LIMIT 100
        ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return $results;
    }

    public function organisationsWaitingByDepartments(Request $request)
    {
        $results = DB::table('structures')
            ->select(['territoires.id', 'territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'structures.department')
            ->whereNull('structures.deleted_at')
            ->whereNotNull('structures.department')
            ->where('structures.state', 'En attente de validation')
            ->where('territoires.type', 'department')
            ->when($this->department, function ($query) {
                $query->where('structures.department', $this->department);
            })
            // ->whereBetween('structures.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id', 'territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function organisationsInProgressByDepartments(Request $request)
    {
        $results = DB::table('structures')
            ->select(['territoires.id', 'territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'structures.department')
            ->whereNull('structures.deleted_at')
            ->whereNotNull('structures.department')
            ->where('structures.state', 'En cours de traitement')
            ->where('territoires.type', 'department')
            ->when($this->department, function ($query) {
                $query->where('structures.department', $this->department);
            })
            // ->whereBetween('structures.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id', 'territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function missionsWaitingByDepartments(Request $request)
    {
        $results = DB::table('missions')
            ->select(['territoires.id', 'territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'missions.department')
            ->whereNull('missions.deleted_at')
            ->whereNotNull('missions.department')
            ->where('missions.state', 'En attente de validation')
            ->where('territoires.type', 'department')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            // ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id', 'territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function missionsInProgressByDepartments(Request $request)
    {
        $results = DB::table('missions')
            ->select(['territoires.id', 'territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'missions.department')
            ->whereNull('missions.deleted_at')
            ->whereNotNull('missions.department')
            ->where('missions.state', 'En cours de traitement')
            ->where('territoires.type', 'department')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            // ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id', 'territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function missionsOutdatedByDepartments(Request $request)
    {
        $results = DB::table('missions')
            ->select(['territoires.id', 'territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'missions.department')
            ->whereNull('missions.deleted_at')
            ->whereNotNull('missions.department')
            ->whereNotNull('missions.end_date')
            ->where('territoires.type', 'department')
            ->where('missions.end_date', '<', date('Y-m-d'))
            ->where('missions.state', 'Validée')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            // ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id', 'territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function participationsCanceledByBenevoles(Request $request)
    {
        return [
            'no_response' => Message::role($request->header('Context-Role'))->where('contextual_state', 'Annulée par bénévole')->where('contextual_reason', 'no_response')->when($this->department, function ($query) {
                $query->whereHas('conversation.conversable', function (Builder $query) {
                    $query->department($this->department);
                });
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'requirements_not_fulfilled' => Message::role($request->header('Context-Role'))->where('contextual_state', 'Annulée par bénévole')->where('contextual_reason', 'requirements_not_fulfilled')->when($this->department, function ($query) {
                $query->whereHas('conversation.conversable', function (Builder $query) {
                    $query->department($this->department);
                });
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'not_available' => Message::role($request->header('Context-Role'))->where('contextual_state', 'Annulée par bénévole')->where('contextual_reason', 'not_available')->when($this->department, function ($query) {
                $query->whereHas('conversation.conversable', function (Builder $query) {
                    $query->department($this->department);
                });
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'other' => Message::role($request->header('Context-Role'))->where('contextual_state', 'Annulée par bénévole')->where('contextual_reason', 'other')->when($this->department, function ($query) {
                $query->whereHas('conversation.conversable', function (Builder $query) {
                    $query->department($this->department);
                });
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function participationsRefusedByResponsables(Request $request)
    {
        return [
            'no_response' => Message::role($request->header('Context-Role'))->where('contextual_state', 'Refusée')->where('contextual_reason', 'no_response')->when($this->department, function ($query) {
                $query->whereHas('conversation.conversable', function (Builder $query) {
                    $query->department($this->department);
                });
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'requirements_not_fulfilled' => Message::role($request->header('Context-Role'))->where('contextual_state', 'Refusée')->where('contextual_reason', 'requirements_not_fulfilled')->when($this->department, function ($query) {
                $query->whereHas('conversation.conversable', function (Builder $query) {
                    $query->department($this->department);
                });
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'mission_terminated' => Message::role($request->header('Context-Role'))->where('contextual_state', 'Refusée')->where('contextual_reason', 'mission_terminated')->when($this->department, function ($query) {
                $query->whereHas('conversation.conversable', function (Builder $query) {
                    $query->department($this->department);
                });
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'change_mind' => Message::role($request->header('Context-Role'))->where('contextual_state', 'Refusée')->where('contextual_reason', 'change_mind')->when($this->department, function ($query) {
                $query->whereHas('conversation.conversable', function (Builder $query) {
                    $query->department($this->department);
                });
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'other' => Message::role($request->header('Context-Role'))->where('contextual_state', 'Refusée')->where('contextual_reason', 'other')->when($this->department, function ($query) {
                $query->whereHas('conversation.conversable', function (Builder $query) {
                    $query->department($this->department);
                });
            })->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function participationsDelaysByRegistrations(Request $request)
    {
        $results = DB::select("
            SELECT
            COUNT(date_difference),
            CASE
                WHEN date_difference <= 60 THEN 'LESS_THAN_1_MIN'
                WHEN date_difference BETWEEN 61 AND 300 THEN 'LESS_THAN_5_MIN'
                WHEN date_difference BETWEEN 301 AND 3600 THEN 'LESS_THAN_1_HOUR'
                WHEN date_difference BETWEEN 3601 AND 84600 THEN 'LESS_THAN_1_DAY'
                WHEN date_difference BETWEEN 84601 AND 423000 THEN 'LESS_THAN_5_DAYS'
                WHEN date_difference > 423000 THEN 'OTHER'
            END delay
            FROM (
                SELECT DISTINCT ON (participations.profile_id)
                participations.profile_id, participations.created_at, profiles.email, profiles.created_at,
                EXTRACT(EPOCH FROM (participations.created_at - profiles.created_at)) AS date_difference
                FROM participations
                LEFT JOIN profiles ON profiles.id = participations.profile_id
                WHERE profiles.created_at BETWEEN :start and :end
                AND profiles.zip ILIKE :department
                ORDER BY participations.profile_id, participations.id ASC
            ) MyTable
            GROUP BY delay
            ", [
            'department' => $this->department ? $this->department.'%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return collect($results)->pluck('count', 'delay');
    }

    public function placesByReseaux(Request $request)
    {
        $results = DB::select("
                SELECT reseaux.name, reseaux.id,
                SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END) AS count
                FROM missions
                LEFT JOIN structures ON structures.id = missions.structure_id
                LEFT JOIN reseau_structure ON reseau_structure.structure_id = structures.id
                LEFT JOIN reseaux ON reseaux.id = reseau_structure.reseau_id
                WHERE structures.deleted_at IS NULL
                AND structures.state = 'Validée'
                AND missions.department ILIKE :department
                AND missions.deleted_at IS NULL
                AND reseaux.name IS NOT NULL
                GROUP BY reseaux.name, reseaux.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
        ]);

        return $results;
    }

    public function placesByOrganisations(Request $request)
    {
        $results = DB::select("
                SELECT structures.name, structures.id,
                SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END) AS count
                FROM missions
                LEFT JOIN structures ON structures.id = missions.structure_id
                WHERE structures.deleted_at IS NULL
                AND structures.state = 'Validée'
                AND missions.department ILIKE :department
                AND missions.deleted_at IS NULL
                AND structures.name IS NOT NULL
                GROUP BY structures.name, structures.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
        ]);

        return $results;
    }

    public function placesByMissions(Request $request)
    {
        $results = DB::select("
                SELECT missions.name, missions.id, mission_templates.title,
                SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END) AS count
                FROM missions
                LEFT JOIN structures ON structures.id = missions.structure_id
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                WHERE structures.deleted_at IS NULL
                AND structures.state = 'Validée'
                AND missions.department ILIKE :department
                AND missions.deleted_at IS NULL
                AND structures.name IS NOT NULL
                GROUP BY missions.name, missions.id, mission_templates.title
                ORDER BY count DESC
                LIMIT 5
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
        ]);

        return $results;
    }

    public function placesByDomaines(Request $request)
    {
        $results = DB::select("
                SELECT domaines.name, domaines.id,
                SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END) AS count
                FROM missions
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN domaines ON domaines.id = mission_templates.domaine_id OR domaines.id = missions.domaine_id OR domaines.id = missions.domaine_secondary_id
                WHERE missions.deleted_at IS NULL
                AND missions.department ILIKE :department
                AND domaines.name IS NOT NULL
                GROUP BY domaines.name, domaines.id
                ORDER BY count DESC
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
        ]);

        return $results;
    }

    public function placesByActivities(Request $request)
    {
        $results = DB::select("
                SELECT activities.name, activities.id,
                SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END) AS count
                FROM missions
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN activities ON activities.id = mission_templates.activity_id OR activities.id = missions.activity_id
                WHERE missions.deleted_at IS NULL
                AND missions.department ILIKE :department
                AND activities.name IS NOT NULL
                GROUP BY activities.name, activities.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
        ]);

        return $results;
    }

    public function structuresByMonth(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('month', structures.created_at) AS created_at,
                date_part('year', structures.created_at) as year,
                date_part('month', structures.created_at) as month,
                count(*) AS structures_total,
                sum(case when structures.state  = 'Brouillon' then 1 else 0 end) as structures_draft,
                sum(case when structures.state  = 'En attente de validation' then 1 else 0 end) as structures_waiting_validation,
                sum(case when structures.state  = 'En cours de traitement' then 1 else 0 end) as structures_being_processed,
                sum(case when structures.state  = 'Validée' then 1 else 0 end) as structures_validated,
                sum(case when structures.state  = 'Signalée' then 1 else 0 end) as structures_signaled,
                sum(case when structures.state  = 'Désinscrite' then 1 else 0 end) as structures_unsubscribed
            FROM structures
            WHERE structures.deleted_at IS NULL
            AND structures.department ILIKE :department
            GROUP BY date_trunc('month', structures.created_at), year, month
            ORDER BY date_trunc('month', structures.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 12])) {
                $results[$index]->structures_validated_variation = (($item->structures_validated - $results[$index + 12]->structures_validated) / $results[$index + 12]->structures_validated) * 100;
            }
        }

        return $results;
    }

    public function structuresByYear(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('year', structures.created_at) AS created_at,
                date_part('year', structures.created_at) as year,
                count(*) AS structures_total,
                sum(case when structures.state  = 'Brouillon' then 1 else 0 end) as structures_draft,
                sum(case when structures.state  = 'En attente de validation' then 1 else 0 end) as structures_waiting_validation,
                sum(case when structures.state  = 'En cours de traitement' then 1 else 0 end) as structures_being_processed,
                sum(case when structures.state  = 'Validée' then 1 else 0 end) as structures_validated,
                sum(case when structures.state  = 'Signalée' then 1 else 0 end) as structures_signaled,
                sum(case when structures.state  = 'Désinscrite' then 1 else 0 end) as structures_unsubscribed
            FROM structures
            WHERE structures.deleted_at IS NULL
            AND structures.department ILIKE :department
            GROUP BY date_trunc('year', structures.created_at), year
            ORDER BY date_trunc('year', structures.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 1])) {
                $results[$index]->structures_validated_variation = (($item->structures_validated - $results[$index + 1]->structures_validated) / $results[$index + 1]->structures_validated) * 100;
            }
        }

        return $results;
    }

    public function missionsByMonth(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('month', missions.created_at) AS created_at,
                date_part('year', missions.created_at) as year,
                date_part('month', missions.created_at) as month,
                count(*) AS missions_total,
                sum(case when missions.state  = 'Brouillon' then 1 else 0 end) as missions_draft,
                sum(case when missions.state  = 'En attente de validation' then 1 else 0 end) as missions_waiting_validation,
                sum(case when missions.state  = 'En cours de traitement' then 1 else 0 end) as missions_being_processed,
                sum(case when missions.state  = 'Validée' then 1 else 0 end) as missions_validated,
                sum(case when missions.state  = 'Signalée' then 1 else 0 end) as missions_signaled,
                sum(case when missions.state  = 'Terminée' then 1 else 0 end) as missions_finished,
                sum(case when missions.state  = 'Annulée' then 1 else 0 end) as missions_canceled,
                sum(case when missions.state IN ('Validée','Terminée') then 1 else 0 end) as missions_posted
            FROM missions
            WHERE missions.deleted_at IS NULL
            AND missions.department ILIKE :department
            GROUP BY date_trunc('month', missions.created_at), year, month
            ORDER BY date_trunc('month', missions.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 12])) {
                $results[$index]->missions_posted_variation = (($item->missions_posted - $results[$index + 12]->missions_posted) / $results[$index + 12]->missions_posted) * 100;
            }
        }

        return $results;
    }

    public function missionsByYear(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('year', missions.created_at) AS created_at,
                date_part('year', missions.created_at) as year,
                count(*) AS missions_total,
                sum(case when missions.state  = 'Brouillon' then 1 else 0 end) as missions_draft,
                sum(case when missions.state  = 'En attente de validation' then 1 else 0 end) as missions_waiting_validation,
                sum(case when missions.state  = 'En cours de traitement' then 1 else 0 end) as missions_being_processed,
                sum(case when missions.state  = 'Validée' then 1 else 0 end) as missions_validated,
                sum(case when missions.state  = 'Signalée' then 1 else 0 end) as missions_signaled,
                sum(case when missions.state  = 'Terminée' then 1 else 0 end) as missions_finished,
                sum(case when missions.state  = 'Annulée' then 1 else 0 end) as missions_canceled,
                sum(case when missions.state IN ('Validée','Terminée') then 1 else 0 end) as missions_posted
            FROM missions
            WHERE missions.deleted_at IS NULL
            AND missions.department ILIKE :department
            GROUP BY date_trunc('year', missions.created_at), year
            ORDER BY date_trunc('year', missions.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 1])) {
                $results[$index]->missions_posted_variation = (($item->missions_posted - $results[$index + 1]->missions_posted) / $results[$index + 1]->missions_posted) * 100;
            }
        }

        return $results;
    }

    public function participationsByMonth(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('month', participations.created_at) AS created_at,
                date_part('year', participations.created_at) as year,
                date_part('month', participations.created_at) as month,
                count(*) AS participations_total,
                sum(case when participations.state  = 'En attente de validation' then 1 else 0 end) as participations_waiting_validation,
                sum(case when participations.state  = 'En cours de traitement' then 1 else 0 end) as participations_being_processed,
                sum(case when participations.state  = 'Validée' then 1 else 0 end) as participations_validated,
                sum(case when participations.state  = 'Refusée' then 1 else 0 end) as participations_refused,
                sum(case when participations.state  = 'Annulée' then 1 else 0 end) as participations_canceled
            FROM participations
            LEFT JOIN missions ON participations.mission_id = missions.id
            WHERE participations.deleted_at IS NULL
            AND missions.department ILIKE :department
            GROUP BY date_trunc('month', participations.created_at), year, month
            ORDER BY date_trunc('month', participations.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 12])) {
                if ($results[$index + 12]->participations_total) {
                    $results[$index]->participations_total_variation = (($item->participations_total - $results[$index + 12]->participations_total) / $results[$index + 12]->participations_total) * 100;
                }
                if ($results[$index + 12]->participations_validated) {
                    $results[$index]->participations_validated_variation = (($item->participations_validated - $results[$index + 12]->participations_validated) / $results[$index + 12]->participations_validated) * 100;
                }
            }

            if($item->participations_total){
                $results[$index]->participations_conversion = ($item->participations_validated / $item->participations_total) * 100;
            }
        }

        return $results;
    }

    public function participationsByYear(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('year', participations.created_at) AS created_at,
                date_part('year', participations.created_at) as year,
                count(*) AS participations_total,
                sum(case when participations.state  = 'En attente de validation' then 1 else 0 end) as participations_waiting_validation,
                sum(case when participations.state  = 'En cours de traitement' then 1 else 0 end) as participations_being_processed,
                sum(case when participations.state  = 'Validée' then 1 else 0 end) as participations_validated,
                sum(case when participations.state  = 'Refusée' then 1 else 0 end) as participations_refused,
                sum(case when participations.state  = 'Annulée' then 1 else 0 end) as participations_canceled
            FROM participations
            LEFT JOIN missions ON participations.mission_id = missions.id
            WHERE participations.deleted_at IS NULL
            AND missions.department ILIKE :department
            GROUP BY date_trunc('year', participations.created_at), year
            ORDER BY date_trunc('year', participations.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 1])) {
                if ($results[$index + 1]->participations_total) {
                    $results[$index]->participations_total_variation = (($item->participations_total - $results[$index + 1]->participations_total) / $results[$index + 1]->participations_total) * 100;
                }
                if ($results[$index + 1]->participations_validated) {
                    $results[$index]->participations_validated_variation = (($item->participations_validated - $results[$index + 1]->participations_validated) / $results[$index + 1]->participations_validated) * 100;
                }
            }
            if($item->participations_total){
                $results[$index]->participations_conversion = ($item->participations_validated / $item->participations_total) * 100;
            }
        }

        return $results;
    }

    public function usersByMonth(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('month', profiles.created_at) AS created_at,
                date_part('year', profiles.created_at) as year,
                date_part('month', profiles.created_at) as month,
                count(*) AS profiles_total
            FROM profiles
            WHERE profiles.department ILIKE :department
            GROUP BY date_trunc('month', profiles.created_at), year, month
            ORDER BY date_trunc('month', profiles.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 12])) {
                $results[$index]->profiles_total_variation = (($item->profiles_total - $results[$index + 12]->profiles_total) / $results[$index + 12]->profiles_total) * 100;
            }
        }

        return $results;
    }

    public function usersByYear(Request $request)
    {
        $results = DB::select("
            SELECT date_trunc('year', profiles.created_at) AS created_at,
                date_part('year', profiles.created_at) as year,
                count(*) AS profiles_total
            FROM profiles
            WHERE profiles.department ILIKE :department
            GROUP BY date_trunc('year', profiles.created_at), year
            ORDER BY date_trunc('year', profiles.created_at) DESC
            ", [
            "department" => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        foreach ($results as $index => $item) {
            if (isset($results[$index + 1])) {
                $results[$index]->profiles_total_variation = (($item->profiles_total - $results[$index + 1]->profiles_total) / $results[$index + 1]->profiles_total) * 100;
            }
        }

        return $results;
    }
}

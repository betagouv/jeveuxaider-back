<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collectivity;
use App\Models\Release;
use App\Models\Structure;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Thematique;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActionController extends Controller
{


    public function index(Request $request)
    {

        $actions =  [];

        $user = $request->user();

        if ($unread_messages_count = $user->getUnreadConversationsCount()) {
            array_push($actions, [
                'type' => 'unread_messages',
                'value' => $unread_messages_count,
            ]);
        }

        $structures = $user->structures;

        foreach ($structures as $structure) {
            if ($waiting_participations_count = $structure->participations->where('state', 'En attente de validation')->count()) {
                array_push($actions, [
                    'type' => 'waiting_participations',
                    'value' => $waiting_participations_count,
                    'structure' => $structure,
                ]);
            }
            if ($outdated_missions_count = $structure->missions->where('end_date', '<', Carbon::now())->count()) {
                array_push($actions, [
                    'type' => 'outdated_missions',
                    'value' => $outdated_missions_count,
                    'structure' => $structure,
                ]);
            }
        }

        //outdated_missions


        return $actions;
    }
}

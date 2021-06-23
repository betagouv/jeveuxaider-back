<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiEngagement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiEngagementController extends Controller
{

    public function myMission(Request $request, Int $id)
    {

        $service = new ApiEngagement();

        $mission = $service->getMyMission($id);

        ray($mission);

        return $mission;
    }
}

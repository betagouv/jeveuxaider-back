<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiEngagement;
use Illuminate\Http\Request;

class ApiEngagementController extends Controller
{
    public function myMission(Request $request, int $id)
    {
        $service = new ApiEngagement();

        $mission = $service->getMyMission($id);

        return $mission;
    }
}

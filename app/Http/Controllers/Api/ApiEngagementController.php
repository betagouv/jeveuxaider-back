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

        return $mission;
    }

    public function statisticsMissions(Request $request)
    {

        $service = new ApiEngagement();

        $statistics = $service->getStatistics([
            'facets' => 'fromPublisherName,source',
            'createdAt' => 'gt:2022-04-25',
            //'createdAt' => 'lt:2022-05-25',
            'toPublisherId' => '5f5931496c7ea514150a818f',
            'type' => 'click',
        ]);

        ray($statistics);

        return $statistics;
    }
}

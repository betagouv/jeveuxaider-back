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

    public function outgoingTrafic(Request $request)
    {

        $service = new ApiEngagement();

        $from = '2022-01-01';
        $to = '2022-09-01';

        $results = $service->getStatistics("facets=fromPublisherName,source,toPublisherName&createdAt=gt:$from&createdAt=lt:$to&fromPublisherId=5f5931496c7ea514150a818f&type=click");

        return [
            'total' => $results['total'],
            'partners' => collect($results['facets']['toPublisherName'])->map(function($item) {
                return [
                    'name' => $item['key'],
                    'outgoing_trafic' => $item['doc_count'],
                ];
            })->toArray()
        ];
    }

    public function incomingTrafic(Request $request)
    {

        $service = new ApiEngagement();

        $from = '2022-01-01';
        $to = '2022-09-01';

        $results = $service->getStatistics("facets=fromPublisherName,source,toPublisherName&createdAt=gt:$from&createdAt=lt:$to&toPublisherId=5f5931496c7ea514150a818f&type=click");

        return  [
            'total' => $results['total'],
            'partners' => collect($results['facets']['fromPublisherName'])->map(function($item) {
                return [
                    'name' => $item['key'],
                    'incoming_trafic' => $item['doc_count'],
                ];
            })->toArray()
        ];
    }

    public function outgoingApplies(Request $request)
    {

        $service = new ApiEngagement();

        $from = '2022-01-01';
        $to = '2022-09-01';

        $results = $service->getStatistics("facets=fromPublisherName,source,toPublisherName&createdAt=gt:$from&createdAt=lt:$to&fromPublisherId=5f5931496c7ea514150a818f&type=apply");

        return [
            'total' => $results['total'],
            'partners' => collect($results['facets']['toPublisherName'])->map(function($item) {
                return [
                    'name' => $item['key'],
                    'outgoing_applies' => $item['doc_count'],
                ];
            })->toArray()
        ];
    }

    public function incomingApplies(Request $request)
    {

        $service = new ApiEngagement();

        $from = '2022-01-01';
        $to = '2022-09-01';

        $results =  $service->getStatistics("facets=fromPublisherName,source,toPublisherName&createdAt=gt:$from&createdAt=lt:$to&toPublisherId=5f5931496c7ea514150a818f&type=apply");

        return [
            'total' => $results['total'],
            'partners' => collect($results['facets']['fromPublisherName'])->map(function($item) {
                return [
                    'name' => $item['key'],
                    'incoming_applies' => $item['doc_count'],
                ];
            })->toArray()
        ];
    }
}

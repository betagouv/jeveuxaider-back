<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiEngagement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiEngagementController extends Controller
{
    public function searchAssociations(Request $request)
    {
        $service = new ApiEngagement();

        $response = $service->findAssociation([
            'name' => $request->input('name')
        ]);

        return $response->json();

    }

    public function myMission(Request $request, int $id)
    {
        $service = new ApiEngagement();

        $mission = $service->getMyMission($id);

        return [
            'mission' => $mission
        ];
    }

    public function outgoingTrafic(Request $request)
    {
        $service = new ApiEngagement();

        $from = Carbon::createFromFormat('Y-m-d', $request->input('startDate'))->hour(0)->minute(0)->second(0);
        $to = Carbon::createFromFormat('Y-m-d', $request->input('endDate'))->hour(23)->minute(59)->second(59);

        $results = $service->getStatistics("size=100&facets=toPublisherName&createdAt=gt:$from&createdAt=lt:$to&fromPublisherId=5f5931496c7ea514150a818f&type=click");

        return [
            'total' => $results['total'],
            'partners' => collect($results['facets']['toPublisherName'])->map(
                function ($item) {
                    return [
                        'name' => $item['key'],
                        'outgoing_trafic' => $item['doc_count'],
                    ];
                }
            )->toArray(),
        ];
    }

    public function incomingTrafic(Request $request)
    {
        $service = new ApiEngagement();

        $from = Carbon::createFromFormat('Y-m-d', $request->input('startDate'))->hour(0)->minute(0)->second(0);
        $to = Carbon::createFromFormat('Y-m-d', $request->input('endDate'))->hour(23)->minute(59)->second(59);

        $results = $service->getStatistics("size=100&facets=fromPublisherName&createdAt=gt:$from&createdAt=lt:$to&toPublisherId=5f5931496c7ea514150a818f&type=click");

        return  [
            'total' => $results['total'],
            'partners' => collect($results['facets']['fromPublisherName'])->map(
                function ($item) {
                    return [
                        'name' => $item['key'],
                        'incoming_trafic' => $item['doc_count'],
                    ];
                }
            )->toArray(),
        ];
    }

    public function outgoingApplies(Request $request)
    {
        $service = new ApiEngagement();

        $from = Carbon::createFromFormat('Y-m-d', $request->input('startDate'))->hour(0)->minute(0)->second(0);
        $to = Carbon::createFromFormat('Y-m-d', $request->input('endDate'))->hour(23)->minute(59)->second(59);

        $results = $service->getStatistics("size=100&facets=toPublisherName&createdAt=gt:$from&createdAt=lt:$to&fromPublisherId=5f5931496c7ea514150a818f&type=apply");

        return [
            'total' => $results['total'],
            'partners' => collect($results['facets']['toPublisherName'])->map(
                function ($item) {
                    return [
                        'name' => $item['key'],
                        'outgoing_applies' => $item['doc_count'],
                    ];
                }
            )->toArray(),
        ];
    }

    public function incomingApplies(Request $request)
    {
        $service = new ApiEngagement();

        $from = Carbon::createFromFormat('Y-m-d', $request->input('startDate'))->hour(0)->minute(0)->second(0);
        $to = Carbon::createFromFormat('Y-m-d', $request->input('endDate'))->hour(23)->minute(59)->second(59);

        $results = $service->getStatistics("size=100&facets=fromPublisherName&createdAt=gt:$from&createdAt=lt:$to&toPublisherId=5f5931496c7ea514150a818f&type=apply");

        return [
            'total' => $results['total'],
            'partners' => collect($results['facets']['fromPublisherName'])->map(
                function ($item) {
                    return [
                        'name' => $item['key'],
                        'incoming_applies' => $item['doc_count'],
                    ];
                }
            )->toArray(),
        ];
    }
}

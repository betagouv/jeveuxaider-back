<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiEngagement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

class ApiEngagementController extends Controller
{
    public $year;
    public $month;
    public $startDate;
    public $endDate;
    public $department;

    public function __construct(Request $request)
    {
        $this->startDate =  Carbon::createFromFormat('Y-m-d',  $request->input('startDate'))->hour(0)->minute(0)->second(0);
        $this->endDate =  Carbon::createFromFormat('Y-m-d',  $request->input('endDate'))->hour(23)->minute(59)->second(59);
        $this->department = $request->input('department');
    }

    public function myMission(Request $request, Int $id)
    {

        $service = new ApiEngagement();

        $mission = $service->getMyMission($id);

        return $mission;
    }

    public function outgoingTrafic(Request $request)
    {

        $service = new ApiEngagement();

        $from = $this->startDate;
        $to = $this->endDate;

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

        $from = $this->startDate;
        $to = $this->endDate;

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

        $from = $this->startDate;
        $to = $this->endDate;

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

        $from = $this->startDate;
        $to = $this->endDate;

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

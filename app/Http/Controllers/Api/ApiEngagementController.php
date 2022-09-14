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

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MissionUserWaitingList;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;

class MissionUserWaitingListController extends Controller
{
    public function index(Request $request)
    {
        $currentUserId = Auth::guard('api')->user()->id;

        return QueryBuilder::for(MissionUserWaitingList::ofUser($currentUserId))
            ->with('mission')
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function delete(Request $request, MissionUserWaitingList $missionUserWaitingList)
    {
        return (string) $missionUserWaitingList->delete();
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Profile;
use App\Models\Structure;

class StatisticsController extends Controller
{
    public function missions(Request $request)
    {
        return [
            'total' => Mission::role($request->header('Context-Role'))->count()
        ];
    }

    public function structures(Request $request)
    {
        return [
            'total' => Structure::role($request->header('Context-Role'))->count(),
        ];
    }

    public function profiles(Request $request)
    {
        return [
            'total' => Profile::role($request->header('Context-Role'))->count(),
        ];
    }
}

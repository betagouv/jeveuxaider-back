<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;

class ChartController extends Controller
{
    public function created(Request $request)
    {
        $year = intval($request->input('year'));
        $items = [];

        switch ($request->input('type')) {
            case 'structures':
                $model = new Structure();
            break;
            case 'missions':
                $model = new Mission();
            break;
            case 'participations':
                $model = new Participation();
            break;
            case 'profiles':
                $model = new Profile();
            break;
        }

        for ($i = 1; $i < 13; $i++) {
            $items[] = $model->role($request->header('Context-Role'))
                ->whereYear('created_at', '=', $year)
                ->whereMonth('created_at', '=', $i)
                ->count();
        }

        return [
            'year' => $year,
            'items' => $items,
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Exports\MissionsExport;
use App\Exports\ParticipationsExport;
use App\Exports\ProfilesExport;
use App\Exports\ReseauxExport;
use App\Exports\StructuresExport;
use App\Exports\TerritoiresExport;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function structures(Request $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        $this->authorize('exportStructures', $currentUser);

        $fileName = 'organisations-' . Str::random(8) . '.xlsx';

        return Excel::download(new StructuresExport($request), $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function missions(Request $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        $this->authorize('exportMissions', $currentUser);

        $fileName = 'missions-' . Str::random(8) . '.xlsx';

        return Excel::download(new MissionsExport($request), $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function participations(Request $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        $this->authorize('exportParticipations', $currentUser);

        $fileName = 'participations-' . Str::random(8) . '.xlsx';

        return Excel::download(new ParticipationsExport($request), $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function territoires(Request $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        $this->authorize('exportTerritoires', $currentUser);

        $fileName = 'territoires-' . Str::random(8) . '.xlsx';

        return Excel::download(new TerritoiresExport($request), $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function reseaux(Request $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        $this->authorize('exportReseaux', $currentUser);

        $fileName = 'reseaux-' . Str::random(8) . '.xlsx';

        return Excel::download(new ReseauxExport($request), $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function profiles(Request $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        $this->authorize('exportProfiles', $currentUser);

        $fileName = 'profiles-' . Str::random(8) . '.xlsx';

        return Excel::download(new ProfilesExport($request), $fileName, \Maatwebsite\Excel\Excel::XLSX);
    }
}

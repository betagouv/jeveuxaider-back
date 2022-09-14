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
        $fileName = 'organisations-'.Str::random(8).'.csv';

        return Excel::download(new StructuresExport($request), $fileName);
    }

    public function missions(Request $request)
    {
        $fileName = 'missions-'.Str::random(8).'.csv';

        return Excel::download(new MissionsExport($request), $fileName);
    }

    public function participations(Request $request)
    {
        $fileName = 'participations-'.Str::random(8).'.csv';

        return Excel::download(new ParticipationsExport($request), $fileName);
    }

    public function territoires(Request $request)
    {
        $fileName = 'territoires-'.Str::random(8).'.csv';

        return Excel::download(new TerritoiresExport(), $fileName);
    }

    public function reseaux(Request $request)
    {
        $fileName = 'reseaux-'.Str::random(8).'.csv';

        return Excel::download(new ReseauxExport($request), $fileName);
    }

    public function profiles(Request $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        if (! $currentUser->profile->can_export_profiles) {
            abort(403, "Vous n'avez pas les droits nécéssaires");
        }

        $fileName = 'profiles-'.Str::random(8).'.csv';

        return Excel::download(new ProfilesExport($request), $fileName);
    }
}

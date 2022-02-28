<?php

namespace App\Http\Controllers\Api;

use App\Exports\StructuresExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyUserOfCompletedExport;
use Illuminate\Support\Str;
use App\Exports\MissionsExport;
use App\Exports\ParticipationsExport;
use App\Exports\TerritoiresExport;
use App\Exports\ReseauxExport;

class ExportController extends Controller
{
    public function structures(Request $request)
    {
        $folder = 'public/' . config('app.env') . '/exports/' . $request->user()->id . '/';
        $fileName = 'organisations-' . Str::random(8) . '.csv';
        $filePath = $folder . $fileName;

        (new StructuresExport($request->header('Context-Role')))
            ->queue($filePath, 's3')
            ->chain([
                new NotifyUserOfCompletedExport($request->user(), $filePath),
            ]);

        return response()->json(['message' => 'Export en cours...'], 200);
    }

    public function missions(Request $request)
    {
        $folder = 'public/' . config('app.env') . '/exports/' . $request->user()->id . '/';
        $fileName = 'missions-' . Str::random(8) . '.csv';
        $filePath = $folder . $fileName;

        (new MissionsExport($request->header('Context-Role')))
            ->queue($filePath, 's3')
            ->chain([
                new NotifyUserOfCompletedExport($request->user(), $filePath),
            ]);

        return response()->json(['message' => 'Export en cours...'], 200);
    }

    public function participations(Request $request)
    {
        $folder = 'public/' . config('app.env') . '/exports/' . $request->user()->id . '/';
        $fileName = 'participations-' . Str::random(8) . '.csv';
        $filePath = $folder . $fileName;

        (new ParticipationsExport($request->header('Context-Role')))
            ->queue($filePath, 's3')
            ->chain([
                new NotifyUserOfCompletedExport($request->user(), $filePath),
            ]);

        return response()->json(['message' => 'Export en cours...'], 200);
    }

    public function territoires(Request $request)
    {
        $folder = 'public/' . config('app.env') . '/exports/' . $request->user()->id . '/';
        $fileName = 'territoires-' . Str::random(8) . '.csv';
        $filePath = $folder . $fileName;

        (new TerritoiresExport())
            ->queue($filePath, 's3')
            ->chain([
                new NotifyUserOfCompletedExport($request->user(), $filePath),
            ]);

        return response()->json(['message' => 'Export en cours...'], 200);
    }

    public function reseaux(Request $request)
    {
        $folder = 'public/' . config('app.env') . '/exports/' . $request->user()->id . '/';
        $fileName = 'reseaux-' . Str::random(8) . '.csv';
        $filePath = $folder . $fileName;

        (new ReseauxExport())
            ->queue($filePath, 's3')
            ->chain([
                new NotifyUserOfCompletedExport($request->user(), $filePath),
            ]);

        return response()->json(['message' => 'Export en cours...'], 200);
    }
}

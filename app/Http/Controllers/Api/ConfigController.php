<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Release;
use App\Models\Structure;
use Illuminate\Support\Facades\Auth;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConfigController extends Controller
{
    public function bootstrap()
    {
        return response()->json([
            'user' => Auth::guard('api')->user(),
            'release' => $this->release(),
            'taxonomies' => $this->taxonomies(),
            'reseaux' => $this->reseaux()
        ]);
    }

    private function release()
    {
        return Release::orderBy('date', 'desc')->first();
    }

    private function reseaux()
    {
        return Structure::where('is_reseau', true)->get()->map(function ($structure) {
            return [
                'id' => $structure->id,
                'name' => $structure->name
            ];
        });
    }

    private function taxonomies()
    {
        $taxonomies = config('taxonomies');
        foreach ($taxonomies as $key => $taxonomy) {
            if (isset($taxonomy['terms'])) {
                $terms = [];
                foreach ($taxonomy['terms'] as $key2 => $term) {
                    $terms[] = [
                        'value' => (string) $key2,
                        'label' => $term,
                    ];
                }
                $taxonomies[$key]['terms'] = $terms;
            }
        }
        return $taxonomies;
    }

    public function export(Request $request, $table)
    {
        $output = '';

        switch ($table) {
            case 'structures':
                $rows = Structure::cursor();
            break;
            case 'missions':
                $rows = Mission::cursor();
            break;
            case 'profiles':
                $rows = Profile::cursor();
            break;
            case 'participations':
                $rows = Participation::cursor();
            break;
        }

        if ($rows) {
            foreach ($rows as $row) {
                $output .=  implode(";", [
                    'id' => $row->id,
                    'first_name' => $row->first_name,
                    'last_name' => $row->last_name
                ])."\n";
            }
        }

        return response()->streamDownload(function () use ($output) {
            echo $output;
        }, 'output.csv');
    }
}

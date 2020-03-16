<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Release;
use App\Models\Structure;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    public function bootstrap()
    {
        return response()->json([
            'user' => Auth::user(),
            'release' => $this->release(),
            'taxonomies' => $this->taxonomies(),
            'reseaux' => $this->reseaux(),
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
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MetadatasRequest;
use App\Models\Metatag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MetatagsController extends Controller
{
    public function update(MetadatasRequest $request, Metatag $metatag)
    {
        $metatag->update($request->validated());

        return $metatag;
    }

    public function store(Request $request, string $modelType, int $modelId)
    {
        $metadatas = Metatag::create([
            'metatagable_type' => 'App\\Models\\'.Str::studly($modelType),
            'metatagable_id' => $modelId,
            'properties' => $request->post('properties'),
        ]);

        return $metadatas;
    }

    public function delete(MetadatasRequest $request, Metatag $metatag)
    {
        return $metatag->delete();
    }
}

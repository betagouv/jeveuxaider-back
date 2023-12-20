<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersNameSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\StructureTagRequest;
use App\Models\Structure;
use App\Models\StructureTag;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;

class StructureTagController extends Controller
{
    public function index(Request $request, Structure $structure)
    {
        $this->authorize('manageTags', $structure);

        return StructureTag::where('structure_id', $structure->id)->get();
    }

    public function store(StructureTagRequest $request, Structure $structure)
    {
        $this->authorize('manageTags', $structure);

        $attributes =  array_merge($request->validated(), [
            'structure_id' => $structure->id
        ]);

        $structureTag = StructureTag::create($attributes);

        return $structureTag;
    }

    public function update(StructureTagRequest $request, Structure $structure, StructureTag $structureTag)
    {
        $this->authorize('manageTags', $structure);

        $structureTag = $structureTag->update($request->validated());

        return $structureTag;
    }

    public function delete(Request $request, Structure $structure, StructureTag $structureTag)
    {
        $this->authorize('manageTags', $structure);

        return (string) $structureTag->delete();
    }
}

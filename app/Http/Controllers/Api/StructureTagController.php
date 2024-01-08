<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StructureTagRequest;
use App\Models\Structure;
use App\Models\StructureTag;
use App\Models\StructureTaggable;
use Illuminate\Http\Request;

class StructureTagController extends Controller
{
    public function index(Request $request, Structure $structure)
    {
        $this->authorize('manageTags', $structure);

        return StructureTag::where('structure_id', $structure->id)->orderBy('name')->get();
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

        if($structureTag->is_generic) {
            abort('422', "Cette étiquette ne peut pas être modifiée.");
        }

        $structureTag = $structureTag->update($request->validated());

        return $structureTag;
    }

    public function delete(Request $request, Structure $structure, StructureTag $structureTag)
    {
        $this->authorize('manageTags', $structure);

        if($structureTag->is_generic) {
            abort('422', "Cette étiquette ne peut pas être supprimée.");
        }

        $relatedEntities = StructureTaggable::where('structure_tag_id', $structureTag->id)->count();

        if ($relatedEntities) {
            abort('422', "Cette étiquette est reliée à {$relatedEntities} entité(s). Elle ne peut pas être supprimée.");
        }

        return (string) $structureTag->delete();
    }
}

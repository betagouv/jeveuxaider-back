<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function store(Request $request, String $modelType, Int $modelId, String $collection, String $attribute)
    {
        $model = $this->getModel($modelType, $modelId);
        $conversions = $this->getConversions($model, $collection);
        $this->deleteCurrentMedia($model, $collection, $attribute);
        return $this->addMedia($request, $conversions, $model, $collection, $attribute);
        // return response()->json($model->{$attribute});
    }

    public function delete(Request $request, Media $media)
    {
        return $media->delete();
    }

    private function getModel(String $modelType, Int $modelId)
    {
        $modelClass = '\\App\\Models\\' . Str::studly($modelType);
        $model = $modelClass::find($modelId);
        return $model->registerMediaConversions();
    }

    private function getConversions($model, $collection)
    {
        return array_filter(array_map(function ($conversion) use ($collection) {
            return $conversion->shouldBePerformedOn($collection) ? $conversion->getName() : null;
        }, $model->mediaConversions));
    }

    private function deleteCurrentMedia($model, $collection, $attribute)
    {
        // Delete previous file
        if ($media = $model->getFirstMedia($collection, ['attribute' => $attribute])) {
            $media->delete();
        }
    }

    private function addMedia(Request $request, $conversions, $model, String $collection, $attribute)
    {
        // Add media
        $cropSettings = json_decode($request->get('cropSettings'));
        $manipulations = [];
        foreach ($conversions as $conversion) {
            $manipulations[$conversion] = [
                'manualCrop' => implode(",", [
                    $cropSettings->width,
                    $cropSettings->height,
                    $cropSettings->left,
                    $cropSettings->top
                ])
            ];
        }

        $model
            ->addMedia($request->file('image'))
            ->withManipulations($manipulations)
            ->toMediaCollection($collection);

        return $model->{$attribute};
    }
}

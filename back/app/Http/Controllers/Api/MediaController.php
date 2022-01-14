<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    public function store(Request $request, String $modelType, Int $modelId, String $collection, String $attribute)
    {
        $model = $this->getModel($modelType, $modelId);
        $media = $model
            ->addMedia($request->file('file'))
            ->withCustomProperties(['attribute' => $attribute])
            ->withManipulations($this->getManipulations($request, $model, $collection))
            ->toMediaCollection($collection);

        return $media->getFormattedMediaField();
    }

    public function delete(Request $request, Media $media)
    {
        return $media->delete();
    }

    private function getModel(String $modelType, Int $modelId)
    {
        $modelClass = '\\App\\Models\\' . Str::studly($modelType);
        $model = $modelClass::find($modelId);
        $model->registerMediaConversions();
        return $model;
    }

    private function getManipulations($request, $model, $collection)
    {
        $conversions = $this->getConversions($model, $collection);
        $manipulations = [];
        $cropSettings = json_decode($request->get('cropSettings'));

        foreach ($conversions as $conversion) {
            $manipulations[$conversion] = [];
            if (!empty($cropSettings)) {
                $manipulations[$conversion]['manualCrop'] = implode(",", [
                    $cropSettings->width,
                    $cropSettings->height,
                    $cropSettings->left,
                    $cropSettings->top
                ]);
            }
        }

        return $manipulations;
    }

    private function getConversions($model, $collection)
    {
        return array_filter(array_map(function ($conversion) use ($collection) {
            return $conversion->shouldBePerformedOn($collection) ? $conversion->getName() : null;
        }, $model->mediaConversions));
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Media;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class MediaController extends Controller
{
    public function store(Request $request, String $modelType, Int $modelId, String $collection, String $attribute)
    {
        $model = $this->getModel($modelType, $modelId);
        $manipulations = json_decode($request->post('manipulations'), true) ?? [];

        $media = $model
            ->addMedia($request->file('file'))
            ->withCustomProperties(['attribute' => $attribute])
            ->withManipulations($this->formatManipulations($manipulations, $model, $collection))
            ->toMediaCollection($collection);

        return $media->getFormattedMediaField();
    }

    public function update(Request $request, Media $media)
    {
        $model = ($media->model_type)::find($media->model_id);
        $model->registerMediaConversions();
        $manipulations = $request->input('manipulations');
        if (!empty($manipulations)) {
            $media->manipulations = $this->formatManipulations($manipulations, $model, $media->collection_name);
            $media->save();

            Artisan::call('media-library:regenerate', [
                '--ids' => $media->id,
                '--force' => true,
            ]);
        }


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

    private function formatManipulations($manipulations, $model, $collection)
    {
        $formattedManipulations = [];
        $conversions = $this->getConversions($model, $collection);
        foreach ($conversions as $conversion) {
            $formattedManipulations[$conversion] = $manipulations;
        }
        return $formattedManipulations;
    }

    private function getConversions($model, $collection)
    {
        return array_filter(array_map(function ($conversion) use ($collection) {
            return $conversion->shouldBePerformedOn($collection) ? $conversion->getName() : null;
        }, $model->mediaConversions));
    }
}

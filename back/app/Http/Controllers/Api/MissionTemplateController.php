<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersTitleBodySearch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MissionTemplateCreateRequest;
use App\Http\Requests\Api\MissionTemplateUpdateRequest;
use App\Http\Requests\Api\MissionTemplateDeleteRequest;
use App\Http\Requests\Api\MissionTemplateUploadRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\MissionTemplate;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Str;

class MissionTemplateController extends Controller
{
    public function index(Request $request)
    {
        $paginate = $request->has('pagination') ? $request->input('pagination') : config('query-builder.results_per_page');

        return QueryBuilder::for(MissionTemplate::with(['domaine']))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersTitleBodySearch),
                AllowedFilter::exact('domaine.id'),
                AllowedFilter::exact('published'),
                AllowedFilter::scope('of_reseau')
            )
            ->defaultSort('-updated_at')
            ->paginate($paginate);
    }

    public function store(MissionTemplateCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        return MissionTemplate::create($request->validated());
    }

    public function show(MissionTemplate $missionTemplate)
    {
        return $missionTemplate;
    }

    public function update(MissionTemplateUpdateRequest $request, MissionTemplate $missionTemplate)
    {
        $missionTemplate->update($request->validated());

        return $missionTemplate;
    }

    public function upload(MissionTemplateUploadRequest $request, MissionTemplate $missionTemplate, String $field)
    {
        // Delete previous file
        if ($media = $missionTemplate->getFirstMedia('templates', ['field' => $field])) {
            $media->delete();
        }

        $extension = $request->file('image')->guessExtension();
        $name = Str::random(15);

        $data = $request->all();
        $cropSettings = json_decode($data['cropSettings']);

        $media = $missionTemplate
            ->addMedia($request->file('image'))
            ->usingName($name)
            ->usingFileName($name . '.' . $extension)
            ->withCustomProperties(['field' => $field]);
        

        if (!empty($cropSettings)) {
            $stringCropSettings = implode(",", [
                $cropSettings->width,
                $cropSettings->height,
                $cropSettings->x,
                $cropSettings->y
            ]);
            $media->withManipulations([
                'thumb' => ['manualCrop' => $stringCropSettings],
                'large' => ['manualCrop' => $stringCropSettings],
                'xxl' => ['manualCrop' => $stringCropSettings]
            ]);
        } else {
            $pathName = $request->file('image')->getPathname();
            $infos = getimagesize($pathName);
            if($infos) {
                $stringCropSettings = implode(",", [
                    $infos[0],
                    $infos[1],
                    0,
                    0
                ]);
                $media->withManipulations([
                    'thumb' => ['manualCrop' => $stringCropSettings],
                    'large' => ['manualCrop' => $stringCropSettings],
                    'xxl' => ['manualCrop' => $stringCropSettings]
                ]);
            }
        }

        $media->toMediaCollection('templates');

        return $missionTemplate;
    }

    public function uploadDelete(MissionTemplateDeleteRequest $request, MissionTemplate $missionTemplate)
    {
        if ($media = $missionTemplate->getFirstMedia('templates')) {
            $media->delete();
        }
    }

    public function delete(Request $request, MissionTemplate $missionTemplate)
    {
        return (string) $missionTemplate->delete();
    }
}

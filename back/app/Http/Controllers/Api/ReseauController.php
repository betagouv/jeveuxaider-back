<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersReseauSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ReseauUploadRequest;
use App\Http\Requests\ReseauRequest;
use App\Models\Profile;
use App\Models\Reseau;
use App\Models\Structure;
use App\Notifications\ReseauNewLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Tag;
use Illuminate\Support\Str;

class ReseauController extends Controller
{

    public function index(Request $request)
    {
        return QueryBuilder::for(Reseau::withCount(['structures', 'missionTemplates', 'missions']))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersReseauSearch),
                AllowedFilter::exact('is_published'),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show($slugOrId)
    {
        $reseau = (is_numeric($slugOrId))
        ? Reseau::where('id', $slugOrId)->withCount('structures', 'missionTemplates', 'invitationsAntennes', 'responsables')->firstOrFail()
        : Reseau::where('slug', $slugOrId)
            ->withCount(['structures' => function ($query) {
                $query->where('state', 'Validée');
            }])
            ->with([
                'structures' => function ($query) {
                    $query->where('state', 'Validée')
                        ->withCount(['missions' => function ($query) {
                            $query->where('state', 'Validée');
                        }])
                        ->orderBy('missions_count', 'DESC')
                        ->limit(5);
                }
            ])
            ->firstOrFail()
            ->append(['domaines_with_image', 'participations_max']);

        return $reseau->append(["domaines", "logo", "override_image_1", "override_image_2"]);
    }

    public function store(ReseauRequest $request)
    {
        $reseau = Reseau::create(
            $request->validated()
        );

        if ($request->has('domaines')) {
            $domaines_ids = collect($request->input('domaines'))->pluck('id');
            $domaines = Tag::whereIn('id', $domaines_ids)->get();
            $reseau->syncTagsWithType($domaines, 'domaine');
        }

        return $reseau;
    }

    public function update(ReseauRequest $request, Reseau $reseau)
    {
        if ($request->has('domaines')) {
            $domaines_ids = collect($request->input('domaines'))->pluck('id');
            $domaines = Tag::whereIn('id', $domaines_ids)->get();
            $reseau->syncTagsWithType($domaines, 'domaine');
        }

        return $reseau->update($request->validated());
    }

    public function attachOrganisations(Request $request, Reseau $reseau)
    {
        if ($request->input('organisations')) {
            $reseau->structures()->syncWithoutDetaching($request->input('organisations'));
        }

        return $reseau;
    }

    public function detachOrganisation(Request $request, Reseau $reseau, Structure $structure)
    {
        $structure->structures()->detach($structure->id);
        return $structure;
    }

    public function lead(Request $request)
    {
        Notification::route('mail', [
            'nassim.merzouk@beta.gouv.fr' => 'Joe',
            'joe.achkar@beta.gouv.fr' => 'Nassim',
        ])->notify(new ReseauNewLead($request->all()));

        return true;
    }

    public function delete(Request $request, Reseau $reseau)
    {
        $this->authorize('delete', $reseau);

        return (string) $reseau->delete();
    }

    public function responsables(Request $request, Reseau $reseau)
    {
        return $reseau->responsables()->orderBy('id')->get();
    }

    public function invitationsResponsables(Request $request, Reseau $reseau)
    {
        return $reseau->invitationsResponsables()->orderBy('id')->get();
    }

    public function deleteResponsable(Request $request, Reseau $reseau, Profile $responsable)
    {
        $reseau->deleteResponsable($responsable);
        return $reseau->responsables;
    }

    public function upload(ReseauUploadRequest $request, Reseau $reseau, String $field)
    {
        // Delete previous file
        if ($media = $reseau->getFirstMedia('reseaux', ['field' => $field])) {
            $media->delete();
        }

        $data = $request->all();
        $extension = $request->file('image')->guessExtension();
        $name = Str::random(30);

        $cropSettings = json_decode($data['cropSettings']);
        if (!empty($cropSettings)) {
            $stringCropSettings = implode(",", [
                $cropSettings->width,
                $cropSettings->height,
                $cropSettings->x,
                $cropSettings->y
            ]);
        } else {
            $pathName = $request->file('image')->getPathname();
            $infos = getimagesize($pathName);
            $stringCropSettings = implode(",", [
                $infos[0],
                $infos[1],
                0,
                0
            ]);
        }

        $reseau
            ->addMedia($request->file('image'))
            ->usingName($name)
            ->usingFileName($name . '.' . $extension)
            ->withCustomProperties(['field' => $field])
            ->withManipulations([
                'thumb' => ['manualCrop' => $stringCropSettings],
                'large' => ['manualCrop' => $stringCropSettings],
                'xxl' => ['manualCrop' => $stringCropSettings]
            ])
            ->toMediaCollection('reseaux');

        return $reseau;
    }

    public function uploadDelete(ReseauUploadRequest $request, Reseau $reseau, String $field)
    {
        if ($media = $reseau->getFirstMedia('reseaux', ['field' => $field])) {
            $media->delete();
        }
    }

    public function structures(Request $request, Reseau $reseau)
    {
        // @todo: corriger orderBy
        return $reseau->structures()
        ->where('state', 'Validée')
        ->where('statut_juridique', 'Association')
        ->orderBy('structures.name')
        ->get();
    }
}

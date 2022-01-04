<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersReseauSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReseauRequest;
use App\Models\Profile;
use App\Models\Reseau;
use App\Models\Structure;
use App\Notifications\ReseauNewLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;


class ReseauController extends Controller
{

    public function index(Request $request)
    {
        return QueryBuilder::for(Reseau::withCount(['structures', 'missionTemplates', 'missions']))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersReseauSearch),
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    // public function show(Request $request, Reseau $reseau)
    // {
    //     $reseau = Reseau::withCount('structures', 'missions', 'missionTemplates','invitationsAntennes','responsables')->where('id', $reseau->id)->first();
    //     return $reseau;
    // }

    // public function store(ReseauRequest $request)
    // {
    //     $reseau = Reseau::create(
    //         $request->validated()
    //     );

    //     return $reseau;
    // }

    // public function update(ReseauRequest $request, Reseau $reseau)
    // {
    //     return $reseau->update($request->validated());
    // }

    // public function attachOrganisations(Request $request, Reseau $reseau)
    // {
    //     if($request->input('organisations')) {
    //         $reseau->structures()->syncWithoutDetaching($request->input('organisations'));
    //     }

    //     return $reseau;
    // }

    // public function detachOrganisation(Request $request, Reseau $reseau, Structure $structure)
    // {

    //     $reseau->structures()->detach($structure->id);

    //     return $reseau;
    // }

    // public function lead(Request $request)
    // {
    //     Notification::route('mail', [
    //         'nassim.merzouk@beta.gouv.fr' => 'Joe',
    //         'joe.achkar@beta.gouv.fr' => 'Nassim',
    //     ])->notify(new ReseauNewLead($request->all()));

    //     return true;
    // }

    // public function delete(Request $request, Reseau $reseau)
    // {
    //     $this->authorize('delete', $reseau);

    //     return (string) $reseau->delete();
    // }

    // public function responsables(Request $request, Reseau $reseau)
    // {
    //     return $reseau->responsables()->orderBy('id')->get();
    // }

    // public function invitationsResponsables(Request $request, Reseau $reseau)
    // {
    //     return $reseau->invitationsResponsables()->orderBy('id')->get();
    // }

    // public function deleteResponsable(Request $request, Reseau $reseau, Profile $responsable)
    // {
    //     $reseau->deleteResponsable($responsable);
    //     return $reseau->responsables;
    // }
}

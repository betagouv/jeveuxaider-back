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
    public function test() 
    {
        // $reseau = Reseau::firstOrCreate(['name' => 'First Réseau']);
        // ray($reseau->missionTemplates);
        // $reseau->structures()->syncWithoutDetaching([3438, 5361, 5374]);
        // $structure = Structure::find(5374);
        // $structure = Structure::find(3438);
        // $reseau->profiles()->saveMany([Profile::find(320048), Profile::find(346484)]);
        // return $reseau->missionTemplates;
    }

    public function index(Request $request)
    {
        return QueryBuilder::for(Reseau::withCount('structures', 'missionTemplates'))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersReseauSearch),
            ])
            ->defaultSort('-updated_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show(Request $request, Reseau $reseau)
    {
        $reseau = Reseau::with(['structures', 'responsables'])->withCount('structures', 'missionTemplates')->where('id', $reseau->id)->first();
        return $reseau;
    }

    public function store(ReseauRequest $request)
    {
        $reseau = Reseau::create(
            $request->validated()
        );

        return $reseau;
    }

    public function update(ReseauRequest $request, Reseau $reseau)
    {
        return $reseau->update($request->validated());
    }

    public function addOrganisation(Request $request, Reseau $reseau)
    {
        if($request->input('organisations')) {
            $reseau->structures()->syncWithoutDetaching($request->input('organisations'));
        }

        return $reseau;
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

    public function organisations(Request $request, Reseau $reseau)
    {
        return $reseau->structures;
    }

    public function responsables(Request $request, Reseau $reseau)
    {
        return $reseau->responsables;
    }

    public function invitations(Request $request, Reseau $reseau)
    {
        return $reseau->invitations;
    }

    public function deleteResponsable(Request $request, Reseau $reseau, Profile $responsable)
    {
        $reseau->deleteResponsable($responsable);
        return $reseau->responsables;
    }
}

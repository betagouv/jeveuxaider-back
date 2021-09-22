<?php

namespace App\Http\Controllers\Api;

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
        $reseau = Reseau::firstOrCreate(['name' => 'First Réseau']);
        ray($reseau->missionTemplates);
        // $reseau->structures()->syncWithoutDetaching([3438, 5361, 5374]);
        // $structure = Structure::find(5374);
        // $structure = Structure::find(3438);
        // $reseau->profiles()->saveMany([Profile::find(320048), Profile::find(346484)]);
        return $reseau->missionTemplates;
    }

    public function index(Request $request)
    {
        return QueryBuilder::for(Reseau::class)
            ->defaultSort('-updated_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show(Request $request, Reseau $reseau) 
    {
        return $reseau;
    }

    public function store(ReseauRequest $request)
    {
        $reseau = Reseau::create(
            $request->validated()
        );

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
}

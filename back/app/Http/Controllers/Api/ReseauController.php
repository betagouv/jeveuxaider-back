<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Reseau;
use App\Models\Structure;
use App\Notifications\ReseauNewLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ReseauController extends Controller
{
    public function test() 
    {
        $reseau = Reseau::firstOrCreate(['name' => 'First Réseau']);
        $reseau->structures()->syncWithoutDetaching([3438, 5361, 5374]);
        $structure = Structure::find(5374);
        $structure = Structure::find(3438);
        $reseau->profiles()->saveMany([Profile::find(320048), Profile::find(346484)]);
        return $reseau->profiles;
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

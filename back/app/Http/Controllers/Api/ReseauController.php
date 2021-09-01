<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\ReseauNewLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ReseauController extends Controller
{
    public function lead(Request $request)
    {
        Notification::route('mail', [
            'nassim.merzouk@beta.gouv.fr' => 'Joe',
            'joe.achkar@beta.gouv.fr' => 'Nassim',
        ])->notify(new ReseauNewLead($request->all()));

        return true;
    }
}

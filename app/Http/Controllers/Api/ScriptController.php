<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ScriptController extends Controller
{
    public function migrateOrganisationMissions(Request $request)
    {
        abort(422, "Vous n'avez pas les droits nécéssaires");

        //
    }
}

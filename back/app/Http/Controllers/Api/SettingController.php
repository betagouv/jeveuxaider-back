<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Settings\MessageSettings;
use App\Settings\EditoSettings;
use Illuminate\Http\Request;

class SettingController
{

    public function messages(MessageSettings $settings)
    {
        return $settings->toArray();
    }

    public function updateMessages(Request $request, MessageSettings $settings)
    {
        $settings->title = $request->input('title');
        $settings->benevole = $request->input('benevole');
        $settings->admin = $request->input('admin');
        $settings->responsable_organisation = $request->input('responsable_organisation');
        $settings->responsable_territoire = $request->input('responsable_territoire');
        $settings->referent_departemental = $request->input('referent_departemental');
        $settings->referent_regional = $request->input('referent_regional');
        $settings->responsable_reseau = $request->input('responsable_reseau');
        $settings->analyste = $request->input('analyste');
        $settings->save();

        return $settings;
    }

    public function edito(EditoSettings $settings)
    {
        return $settings->toArray();
    }

    public function updateEdito(Request $request, EditoSettings $settings)
    {
        $settings->missions_prioritaires = [];
        return $settings;
    }

}

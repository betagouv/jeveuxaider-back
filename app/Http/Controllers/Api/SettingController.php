<?php

namespace App\Http\Controllers\Api;

use App\Settings\GeneralSettings;
use App\Settings\MessageSettings;
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

    public function general(GeneralSettings $settings)
    {
        return $settings->toArray();
    }

    public function updateGeneral(Request $request, GeneralSettings $settings)
    {
        $settings->blog_active = $request->input('blog_active');
        $settings->snu_mig_active = $request->input('snu_mig_active');
        $settings->maintenance_mode_active = $request->input('maintenance_mode_active');
        $settings->light_mode_active = $request->input('light_mode_active');
        $settings->france_connect_active = $request->input('france_connect_active');

        $settings->save();

        return $settings;
    }
}

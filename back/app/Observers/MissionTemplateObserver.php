<?php

namespace App\Observers;

use App\Models\Mission;
use App\Models\MissionTemplate;

class MissionTemplateObserver
{

    public function updated(MissionTemplate $missionTemplate)
    {
        if ($missionTemplate->isDirty('subtitle')) {
            Mission::where('template_id', $missionTemplate->id)->get()->map(function ($mission) {
                if ($mission->structure) {
                    if ($mission->structure->state == 'Validée' && $mission->state == 'Validée') {
                        $mission->searchable();
                    }
                }
            });
        }
    }
}

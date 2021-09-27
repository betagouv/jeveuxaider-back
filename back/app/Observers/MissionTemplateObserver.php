<?php

namespace App\Observers;

use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Notifications\MissionTemplateCreated;
use Illuminate\Support\Facades\Notification;

class MissionTemplateObserver
{
    public function created(MissionTemplate $missionTemplate)
    {
        if($missionTemplate->reseau_id) {
            Notification::route('mail', ['giulietta.bressy@gmail.com', 'nassim.merzouk@beta.gouv.fr'])->notify(new MissionTemplateCreated($missionTemplate));
        }
    }

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
        
        // TODO 
        // If missionTemplate->reseau
            // If isDirty published and published is true
                // Notif email resonsables tête de réseau
                // Notif email aux antennes ?
    }
}

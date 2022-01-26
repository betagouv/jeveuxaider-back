<?php

namespace App\Observers;

use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Notifications\MissionTemplateWaiting;
use App\Notifications\MissionTemplateUpdated;
use Illuminate\Support\Facades\Notification;

class MissionTemplateObserver
{
    public function created(MissionTemplate $missionTemplate)
    {
        if($missionTemplate->reseau_id && $missionTemplate->state == 'waiting') {
            Notification::route('mail', ['giulietta.bressy@gmail.com', 'nassim.merzouk@beta.gouv.fr'])->notify(new MissionTemplateWaiting($missionTemplate));
        }
    }

    public function updated(MissionTemplate $missionTemplate)
    {

        if($missionTemplate->reseau_id && $missionTemplate->isDirty('state') && $missionTemplate->state == 'waiting') {
            Notification::route('mail', ['giulietta.bressy@gmail.com', 'nassim.merzouk@beta.gouv.fr'])->notify(new MissionTemplateWaiting($missionTemplate));
        }

        $changes = $missionTemplate->getChanges();

        if (isset($changes['title']) || isset($changes['subtitle'])) {
            Mission::where('template_id', $missionTemplate->id)->get()->map(function ($mission) {
                if($mission->shouldBeSearchable()){
                    $mission->searchable();
                }
            });
        }

        if($missionTemplate->reseau_id && $missionTemplate->published) {
            if(isset($changes) && count($changes) > 1) { // ignore updated_at
                Notification::route('mail', ['giulietta.bressy@gmail.com', 'nassim.merzouk@beta.gouv.fr'])->notify(new MissionTemplateUpdated($missionTemplate, $missionTemplate->getOriginal(), $changes));
            }
        }

    }
}

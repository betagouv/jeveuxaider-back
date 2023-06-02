<?php

namespace App\Observers;

use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Notifications\MissionTemplateUpdated;
use App\Notifications\MissionTemplateWaiting;
use Illuminate\Support\Facades\Notification;

class MissionTemplateObserver
{

    private $mailsToNotify = [
        'sophie.galent@beta.gouv.fr',
        'coralie.chauvin@beta.gouv.fr',
        'nivine.katanji@beta.gouv.fr'
    ];

    public function created(MissionTemplate $missionTemplate)
    {
        if ($missionTemplate->reseau_id && $missionTemplate->state == 'waiting') {
            Notification::route('mail', $this->mailsToNotify)->notify(new MissionTemplateWaiting($missionTemplate));
        }
    }

    public function updated(MissionTemplate $missionTemplate)
    {
        if ($missionTemplate->reseau_id && $missionTemplate->isDirty('state') && $missionTemplate->state == 'waiting') {
            Notification::route('mail', $this->mailsToNotify)->notify(new MissionTemplateWaiting($missionTemplate));
        }

        $changes = $missionTemplate->getChanges();

        if (isset($changes['title']) || isset($changes['subtitle']) || $missionTemplate->isDirty('activity_id') || $missionTemplate->isDirty('domaine_id') || $missionTemplate->isDirty('domaine_secondary_id')) {
            $missionTemplate->missions()->with(['structure'])->searchable();
        }

        if ($missionTemplate->reseau_id && $missionTemplate->published && ! $missionTemplate->isDirty('state')) {
            if (isset($changes) && count($changes) > 1) { // ignore updated_at
                Notification::route('mail', $this->mailsToNotify)->notify(new MissionTemplateUpdated($missionTemplate, $missionTemplate->getOriginal(), $changes));
            }
        }
    }
}

<?php

namespace App\Notifications;

use App\Models\Participation;
use App\Models\User;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class BulkOperationsParticipationsValidated extends Notification
{
    public $ids;

    public $currentUser;

    public $participations;

    public function __construct($ids, $userId)
    {
        $this->ids = $ids;
        $this->participations = Participation::with(['profile', 'mission', 'mission.structure', 'mission.responsables'])->whereIn('id', $ids)->get()->map(function ($participation) {
            return [
                'id' => $participation->id,
                'structureId' => $participation->mission->structure->id,
                'structureName' => $participation->mission->structure->name,
                'responsable' => $participation->mission->responsable[0]->full_name,
            ];
        })->unique('responsable');
        $this->currentUser = User::find($userId);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $ids = $this->ids;
        $currentUser = $this->currentUser;
        $participations = $this->participations;
        $from = config('app.env') != 'production' ? '['.config('app.env').'] JeVeuxAider.gouv.fr' : 'JeVeuxAider.gouv.fr';

        return (new SlackMessage)
            ->from($from)
            ->success()
            ->to('#bulk-operation')
            ->attachment(function ($attachment) use ($ids, $currentUser, $participations) {
                $url = url(config('app.front_url')).'/admin/participations?filter[id]='.implode(',', $ids);
                $urlProfile = url(config('app.front_url')).'/admin/utilisateurs/'.$currentUser->profile->id;
                $outputResponsables = [];
                foreach ($participations as $participation) {
                    $urlOrganisation = url(config('app.front_url')).'/admin/organisations/'.$participation['structureId'];
                    $outputResponsables[] = $participation['responsable'].' (<'.$urlOrganisation.'|'.$participation['structureName'].'>)';
                }
                $attachment->color('#2FB887')->content('<'.$urlProfile.'|'.$currentUser->profile->full_name.'> a *validé* <'.$url.'|'.count($ids).' '.Str::plural('participation', count($ids)).">\n".implode(', ', $ids)."\n\n".Str::plural('Responsable', count($outputResponsables))." :\n".implode("\n", $outputResponsables));
            });
    }
}

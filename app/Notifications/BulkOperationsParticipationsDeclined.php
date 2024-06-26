<?php

namespace App\Notifications;

use App\Models\Participation;
use App\Models\User;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class BulkOperationsParticipationsDeclined extends Notification
{
    public $ids;
    public $currentUser;
    public $reason;
    public $content;
    public $participations;

    public function __construct($ids, $userId, $reason, $content)
    {
        $this->ids = $ids;
        $this->currentUser = User::find($userId);
        $this->reason = $reason;
        $this->content = $content;
        $this->participations = Participation::with(['profile', 'mission', 'mission.structure', 'mission.responsables'])->whereIn('id', $ids)->get()->map(function ($participation) {
            return [
                'id' => $participation->id,
                'structureId' => $participation->mission->structure->id,
                'structureName' => $participation->mission->structure->name,
                'responsables' => $participation->mission->responsables->pluck('full_name')->implode(', ', 'full_name'),
            ];
        });
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
        $reason = $this->reason;
        $content = $this->content;
        $participations = $this->participations;
        $from = config('app.env') != 'production' ? '[' . config('app.env') . '] JeVeuxAider.gouv.fr' : 'JeVeuxAider.gouv.fr';

        return (new SlackMessage())
            ->from($from)
            ->success()
            ->to('#bulk-operation')
            ->attachment(function ($attachment) use ($ids, $currentUser, $reason, $content, $participations) {
                $url = url(config('app.front_url')) . '/admin/participations?filter[id]=' . implode(',', $ids);
                $urlProfile = url(config('app.front_url')) . '/admin/utilisateurs/' . $currentUser->profile->id;
                $precisions = empty($content) ? '-' : $content;
                $outputResponsables = [];
                foreach ($participations as $participation) {
                    $urlOrganisation = url(config('app.front_url')) . '/admin/organisations/' . $participation['structureId'];
                    $outputResponsables[] = $participation['responsables'] . ' (<' . $urlOrganisation . '|' . $participation['structureName'] . '>)';
                }

                $attachment
                    ->color('#FF0000')
                    ->content('<' . $urlProfile . '|' . $currentUser->profile->full_name . '> a *refusé* <' . $url . '|' . count($ids) . ' ' . Str::plural('participation', count($ids)) . ">\n" . implode(', ', $ids) . "\n*Raison:* " . $reason . "\n*Précisions:* " . $precisions . "\n\n" . Str::plural('Responsable', count($outputResponsables)) . " :\n" . implode("\n", $outputResponsables));
            });
    }
}

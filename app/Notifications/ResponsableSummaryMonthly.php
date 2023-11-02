<?php

namespace App\Notifications;

use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\User;
use App\Traits\TransactionalEmail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResponsableSummaryMonthly extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $startDate;
    public $endDate;
    public $structure;
    public $profile;
    public $user;
    public $newMissionsCount;
    public $missionsOnlineCount;
    public $newParticipationsCount;
    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($responsableId)
    {
        $this->startDate = Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d H:i:s');
        $this->endDate = Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d H:i:s');

        $this->profile = Profile::find($responsableId);
        $this->user = User::find($this->profile->user_id);
        $this->structure = $this->user->structures->first();

        $this->newMissionsCount = Mission::ofStructure($this->structure->id)->whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->missionsOnlineCount = Mission::ofStructure($this->structure->id)->available()->count();
        $this->newParticipationsCount = Participation::ofStructure($this->structure->id)->whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->tag = 'app-responsable-bilan-mensuel';
    }

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage())
            ->subject($this->profile->first_name . ', découvrez votre résumé mensuel d’activité sur JeVeuxaider.gouv.fr !')
            ->tag($this->tag)
            ->markdown('emails.bilans.responsable-summary-monthly', [
                'notifiable' => $notifiable,
                'url' => $this->trackedUrl('/dashboard'),
                'structure' => $this->structure,
                'variables' => [
                    'newMissionsCount' => $this->newMissionsCount,
                    'missionsOnlineCount' => $this->missionsOnlineCount,
                    'newParticipationsCount' => $this->newParticipationsCount
                ],
            ]);

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

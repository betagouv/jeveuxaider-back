<?php

namespace App\Notifications;

use App\Helpers\Utils;
use App\Models\Message;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ResponsableSummaryMonthly extends Notification implements ShouldQueue
{
    use Queueable;

    public $startDate;
    public $endDate;
    public $structure;
    public $profile;
    public $user;
    public $newMissionsCount;
    public $missionsOnlineCount;
    public $newParticipationsCount;

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

        $this->newMissionsCount = Mission::ofResponsable($responsableId)->whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->missionsOnlineCount = Mission::ofResponsable($responsableId)->available()->count();
        $this->newParticipationsCount = Participation::ofResponsable($responsableId)->whereBetween('created_at', [$this->startDate, $this->endDate])->count();
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
        $mailMessage = (new MailMessage)
            ->subject($this->profile->first_name . ', découvrez votre résumé mensuel d’activité sur JeVeuxaider.gouv.fr !')
            ->tag('app-responsable-bilan-mensuel')
            ->markdown('emails.bilans.responsable-summary-monthly', [
                'notifiable' => $notifiable,
                'url' => url(config('app.front_url') . '/dashboard'),
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

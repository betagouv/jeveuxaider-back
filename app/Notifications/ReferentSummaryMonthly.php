<?php

namespace App\Notifications;

use App\Helpers\Utils;
use App\Models\Message;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ReferentSummaryMonthly extends Notification implements ShouldQueue
{
    use Queueable;

    public $startDate;
    public $endDate;
    public $profile;
    public $user;
    public $department;

    public $newStructuresCount;
    public $newMissionsCount;
    public $newParticipationsCount;
    public $newBenevolesCount;
    public $structuresActivesCount;
    public $missionsOnlineCount;
    public $placesLeftCount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($referentId)
    {
        $this->startDate = Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d H:i:s');
        $this->endDate = Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d H:i:s');

        $this->profile = Profile::find($referentId);
        $this->user = User::find($this->profile->user_id);
        $this->department = $this->user->departmentsAsReferent->first();

        $this->newStructuresCount = Structure::department($this->department->number)->whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->newMissionsCount = Structure::department($this->department->number)->whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->newParticipationsCount = Participation::department($this->department->number)->whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->newBenevolesCount = Profile::department($this->department->number)->whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->structuresActivesCount = Structure::where('state', 'Validée')->whereHas('missions', function(Builder $query){
            $query->available();
        })->department($this->department->number)->count();
        $this->missionsOnlineCount = Mission::department($this->department->number)->available()->count();
        $this->placesLeftCount = Mission::department($this->department->number)->available()->sum('places_left');
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
            ->subject($this->department->name . ' - Résumé mensuel de votre activité en '. Carbon::now()->subMonth()->translatedFormat('F Y'))
            ->tag('app-referent-bilan-mensuel')
            ->markdown('emails.bilans.referent-summary-monthly', [
                'notifiable' => $notifiable,
                'url' => url(config('app.front_url') . '/admin/statistics'),
                'department' => $this->department,
                'variables' => [
                    'newStructuresCount' => $this->newStructuresCount,
                    'newMissionsCount' => $this->newMissionsCount,
                    'newParticipationsCount' => $this->newParticipationsCount,
                    'newBenevolesCount' => $this->newBenevolesCount,
                    'structuresActivesCount' => $this->structuresActivesCount,
                    'missionsOnlineCount' => $this->missionsOnlineCount,
                    'placesLeftCount' => $this->placesLeftCount
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

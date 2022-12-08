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

class ReferentSummaryDaily extends Notification implements ShouldQueue
{
    use Queueable;

    public $profile;
    public $user;
    public $department;
    public $newMessagesCount;
    public $newMissionsCount;
    public $newOrganisationsCount;
    public $missionsWaitingCount;
    public $missionsProcessingCount;
    public $organisationsWaitingCount;
    public $organisationsProcessingCount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($referentId)
    {
        $yesterday = date("Y-m-d", strtotime( '-1 days' ) );

        $this->profile = Profile::find($referentId);
        $this->user = User::find($this->profile->user_id);
        $this->department = $this->user->departmentsAsReferent->first();
        $this->newMessagesCount = Message::whereHas('conversation', function (Builder $query){
            $query->whereHas('users', function (Builder $query){
                $query->where('users.id', $this->user->id);
            });
        })->whereDate('created_at', $yesterday)->count();
        $this->newMissionsCount = Mission::department($this->department->number)->whereDate('created_at', $yesterday)->count();
        $this->newOrganisationsCount = Structure::department($this->department->number)->whereDate('created_at', $yesterday)->count();
        $this->missionsWaitingCount = Mission::department($this->department->number)->where('state', 'En attente de validation')->count();
        $this->missionsProcessingCount = Mission::department($this->department->number)->where('state', 'En cours de traitement')->count();
        $this->organisationsWaitingCount = Structure::department($this->department->number)->where('state', 'En attente de validation')->count();
        $this->organisationsProcessingCount = Structure::department($this->department->number)->where('state', 'En cours de traitement')->count();
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
            ->subject($this->department->name . ' - Résumé de la journée du ' . Carbon::yesterday()->translatedFormat('d F Y'))
            ->tag('app-referent-bilan-quotidien')
            ->markdown('emails.bilans.referent-summary-daily', [
                'notifiable' => $notifiable,
                'department' => $this->department,
                'url' => url(config('app.front_url') . '/dashboard'),
                'variables' => [
                    'newMessagesCount' => $this->newMessagesCount,
                    'newMissionsCount' => $this->newMissionsCount,
                    'newOrganisationsCount' => $this->newOrganisationsCount,
                    'missionsWaitingCount' => $this->missionsWaitingCount,
                    'missionsProcessingCount' => $this->missionsProcessingCount,
                    'organisationsProcessingCount' => $this->organisationsProcessingCount,
                    'organisationsProcessingCount' => $this->organisationsProcessingCount
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

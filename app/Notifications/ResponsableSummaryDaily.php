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

class ResponsableSummaryDaily extends Notification implements ShouldQueue
{
    use Queueable;

    public $profile;
    public $user;
    public $newMessagesCount;
    public $newParticipationsCount;
    public $participationsWaitingCount;
    public $participationsProcessingCount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($responsableId)
    {
        $yesterday = date("Y-m-d", strtotime( '-1 days' ) );

        $this->profile = Profile::find($responsableId);
        $this->user = User::find($this->profile->user_id);
        $this->newMessagesCount = Message::whereHas('conversation', function (Builder $query){
            $query->whereHas('users', function (Builder $query){
                $query->where('users.id', $this->user->id);
            });
        })->whereDate('created_at', $yesterday)->count();
        $this->newParticipationsCount = Participation::ofResponsable($responsableId)->whereDate('created_at', $yesterday)->count();
        $this->participationsWaitingCount = Participation::ofResponsable($responsableId)->where('state', 'En attente de validation')->count();
        $this->participationsProcessingCount = Participation::ofResponsable($responsableId)->where('state', 'En cours de traitement')->count();
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
            ->subject('Résumé de la journée du ' . Carbon::yesterday()->translatedFormat('d F Y'))
            ->tag('app-responsable-bilan-quotidien')
            ->markdown('emails.bilans.responsable-summary-daily', [
                'notifiable' => $notifiable,
                'url' => url(config('app.front_url') . '/dashboard'),
                'variables' => [
                    'newParticipationsCount' => $this->newParticipationsCount,
                    'newMessagesCount' => $this->newMessagesCount,
                    'participationsWaitingCount' => $this->participationsWaitingCount,
                    'participationsProcessingCount' => $this->participationsProcessingCount
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

<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\Mission;
use App\Models\MissionUserWaitingList;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\User;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ResponsableSummaryDaily extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $structure;
    public $profile;
    public $user;
    public $newMessagesCount;
    public $newParticipationsCount;
    public $participationsWaitingCount;
    public $participationsProcessingCount;
    public $conversationsUnreadCount;
    public $newUsersInWaitingListCount;
    public $missionsWithNoPlaceLeftCount;
    public $missionsWithRegistrationClosedCount;
    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($responsableId)
    {
        $yesterday = date("Y-m-d", strtotime('-1 days'));

        $this->profile = Profile::find($responsableId);
        $this->user = User::find($this->profile->user_id);
        $this->structure = $this->user->structures->first();
        $this->newMessagesCount = Message::whereHas('conversation', function (Builder $query) {
            $query->whereHas('users', function (Builder $query) {
                $query->where('users.id', $this->user->id);
            });
        })->whereDate('created_at', $yesterday)->count();
        $this->newParticipationsCount = Participation::ofResponsable($responsableId)->whereDate('created_at', $yesterday)->count();
        $this->participationsWaitingCount = Participation::ofResponsable($responsableId)->whereIn('state', ['En attente de validation', 'En cours de traitement'])->count();
        $this->conversationsUnreadCount =  $this->user->getUnreadConversationsCount();
        $this->newUsersInWaitingListCount = MissionUserWaitingList::whereHas('mission', function (Builder $query) use ($responsableId) {
            $query->ofResponsable($responsableId);
        })->whereDate('created_at', $yesterday)->count();

        $this->missionsWithNoPlaceLeftCount = Mission::available()->ofResponsable($responsableId)->where('places_left', 0)->count();
        $this->missionsWithRegistrationClosedCount = Mission::available()->ofResponsable($responsableId)->where('is_registration_open', false)->count();

        $this->tag = 'app-responsable-bilan-quotidien';
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
            ->subject($this->profile->first_name . ', découvrez l’activité du jour sur JeVeuxAider.gouv.fr !')
            ->tag($this->tag)
            ->markdown('emails.bilans.responsable-summary-daily', [
                'notifiable' => $notifiable,
                'url' => $this->trackedUrl('/dashboard'),
                'structure' => $this->structure,
                'variables' => [
                    'newParticipationsCount' => $this->newParticipationsCount,
                    'newMessagesCount' => $this->newMessagesCount,
                    'participationsWaitingCount' => $this->participationsWaitingCount,
                    'participationsProcessingCount' => $this->participationsProcessingCount,
                    'conversationsUnreadCount' => $this->conversationsUnreadCount,
                    'newUsersInWaitingListCount' => $this->newUsersInWaitingListCount,
                    'missionsWithNoPlaceLeftCount' => $this->missionsWithNoPlaceLeftCount,
                    'missionsWithRegistrationClosedCount' => $this->missionsWithRegistrationClosedCount,
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

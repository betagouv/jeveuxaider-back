<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\Mission;
use App\Models\Note;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\User;
use App\Traits\TransactionalEmail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;

class ReferentSummaryDaily extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $profile;
    public $user;
    public $department;
    public $newStructuresCount;
    public $newMissionsCount;
    public $newMessagesCount;
    public $newNotesCount;
    public $structuresWaitingCount;
    public $missionsWaitingCount;
    public $conversationsUnreadCount;
    public $startDate;
    public $endDate;
    public $tag;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($referentId)
    {
        $this->startDate = Carbon::now()->subDays(3)->startOfDay()->format('Y-m-d H:i:s');
        $this->endDate = Carbon::now()->format('Y-m-d H:i:s');

        $this->profile = Profile::find($referentId);
        $this->user = User::find($this->profile->user_id);
        $this->department = $this->user->departmentsAsReferent->first();

        $this->newMessagesCount = Message::whereHas('conversation', function (Builder $query) {
            $query->whereHas('users', function (Builder $query) {
                $query->where('users.id', $this->user->id);
            });
        })->whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->newMissionsCount = Mission::department($this->department->number)->whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->newStructuresCount = Structure::department($this->department->number)->whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->newNotesCount = Note::whereHas('notable', function (Builder $query) {
            $query->department($this->department->number);
        })->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        $this->missionsWaitingCount = Mission::department($this->department->number)->whereIn('state', ['En attente de validation', 'En cours de traitement'])->count();
        $this->structuresWaitingCount = Structure::department($this->department->number)->whereIn('state', ['En attente de validation', 'En cours de traitement'])->count();
        $this->conversationsUnreadCount = $this->user->getUnreadConversationsCount();

        $this->tag = 'app-referent-bilan-quotidien';
    }

    public function shouldSend($notifiable, $channel)
    {
        return $this->conversationsUnreadCount || $this->structuresWaitingCount || $this->missionsWaitingCount || $this->newNotesCount || $this->newMissionsCount || $this->newStructuresCount || $this->newMessagesCount;
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
            ->subject($this->profile->first_name . ', on vous passe le relais !')
            ->tag($this->tag)
            ->markdown('emails.bilans.referent-summary-daily', [
                'notifiable' => $notifiable,
                'department' => $this->department,
                'url' => $this->trackedUrl('/dashboard'),
                'variables' => [
                    'newMessagesCount' => $this->newMessagesCount,
                    'newMissionsCount' => $this->newMissionsCount,
                    'newStructuresCount' => $this->newStructuresCount,
                    'newNotesCount' => $this->newNotesCount,
                    'missionsWaitingCount' => $this->missionsWaitingCount,
                    'structuresWaitingCount' => $this->structuresWaitingCount,
                    'conversationsUnreadCount' => $this->conversationsUnreadCount,
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

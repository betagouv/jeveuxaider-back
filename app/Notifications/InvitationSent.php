<?php

namespace App\Notifications;

use App\Models\Invitation;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationSent extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $invitation;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
        $this->setTag();
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

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        switch ($this->invitation->role) {
            case 'responsable_organisation':
                $message = $this->inviteResponsableOrganization();
                break;
            case 'responsable_territoire':
                $message = $this->inviteResponsableTerritoire();
                break;
            case 'responsable_antenne':
                $message = $this->inviteResponsableAntenne();
                break;
            case 'responsable_reseau':
                $message = $this->inviteResponsableReseau();
                break;
            case 'referent_departemental':
                $message = $this->inviteReferentDepartemental();
                break;
            case 'referent_regional':
                $message = $this->inviteReferentRegional();
                break;
            case 'datas_analyst':
                $message = $this->inviteDatasAnalyst();
                break;
            case 'benevole':
                $message = $this->inviteBenevole();
                break;

            default:
                $message = $this->inviteDefault();
                break;
        }

        return $message;
    }

    private function inviteResponsableOrganization()
    {
        $message = (new MailMessage())
            ->subject($this->invitation->user->profile->first_name . ' de ' . $this->invitation->invitable->name . ' vous invite à créer un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name . " vous invite à rejoindre la plateforme JeVeuxAider.gouv.fr afin de gérer l'organisation " . $this->invitation->invitable->name)
            ->action("Voir l'invitation", $this->trackedUrl("/invitations/" . $this->invitation->token))
            ->line('Une fois l’invitation acceptée, vous pourrez facilement publier des missions en vue de recruter des bénévoles.')
            ->line('En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support Utilisateurs !')
            ->tag($this->tag);

        return $message;
    }

    private function inviteResponsableTerritoire()
    {
        $message = (new MailMessage())
            ->subject($this->invitation->user->profile->first_name . ' de ' . $this->invitation->invitable->name . ' vous invite à créer un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name . ' vous invite à rejoindre la plateforme JeVeuxAider.gouv.fr afin de gérer le territoire ' . $this->invitation->invitable->name)
            ->action("Voir l'invitation", $this->trackedUrl('/invitations/' . $this->invitation->token))
            ->line('Une fois l’invitation acceptée, vous pourrez facilement publier des missions en vue de recruter des bénévoles.')
            ->line('En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support Utilisateurs !')
            ->tag($this->tag);

        return $message;
    }

    private function inviteResponsableAntenne()
    {
        $message = (new MailMessage())
            ->subject($this->invitation->user->profile->first_name . ' de ' . $this->invitation->invitable->name . ' vous invite à créer un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name . " vous invite à créer un compte pour l'organisation " . $this->invitation->properties['antenne_name'] . ' sur JeVeuxAider.gouv.fr.')
            ->action("Voir l'invitation", $this->trackedUrl('/invitations/' . $this->invitation->token))
            ->line('Une fois l’invitation acceptée, vous serez rattaché au réseau ' . $this->invitation->invitable['name'] . ' et pourrez facilement publier des missions en vue de recruter des bénévoles.')
            ->line('En cas de besoin, vous pouvez répondre à ce e-mail pour échanger directement avec le support Utilisateurs !')
            ->tag($this->tag);

        return $message;
    }

    private function inviteResponsableReseau()
    {
        $message = (new MailMessage())
            ->subject($this->invitation->user->profile->first_name . ' de ' . $this->invitation->invitable->name . ' vous invite à créer un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name . ' vous invite à superviser le réseau ' . $this->invitation->invitable->name . ' sur JeVeuxAider.gouv.fr')
            ->action("Voir l'invitation", $this->trackedUrl('/invitations/' . $this->invitation->token))
            ->line("Une fois l'invitation acceptée, vous pourrez facilement piloter l'activité de votre réseau associatif sur la plateforme JeVeuxAider.gouv.fr.")
            ->line('En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support Utilisateurs !')
            ->tag($this->tag);

        return $message;
    }

    private function inviteReferentDepartemental()
    {
        $departmentNumber = $this->invitation->properties['referent_departemental'];
        $departmentName = config('taxonomies.departments.terms')[$departmentNumber];

        $message = (new MailMessage())
            ->subject($this->invitation->user->profile->first_name . ' vous invite à créer un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name . ' vous invite à devenir référent du département ' . $departmentName . ' (' . $departmentNumber . ') sur la plateforme JeVeuxAider.gouv.fr')
            ->action("Voir l'invitation", $this->trackedUrl('/invitations/' . $this->invitation->token))
            ->line("Une fois l’invitation acceptée, vous pourrez facilement suivre l'activité de la Réserve Civique sur votre département.")
            ->line('En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support Utilisateurs !')
            ->tag($this->tag);

        return $message;
    }

    private function inviteReferentRegional()
    {
        $message = (new MailMessage())
            ->subject($this->invitation->user->profile->first_name . ' vous invite à créer un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name . ' vous invite à devenir référent de la région ' . $this->invitation->properties['referent_regional'] . ' sur la plateforme JeVeuxAider.gouv.fr')
            ->action("Voir l'invitation", $this->trackedUrl('/invitations/' . $this->invitation->token))
            ->line("Une fois l’invitation acceptée, vous pourrez facilement suivre l'activité de la Réserve Civique sur votre région.")
            ->line('En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support Utilisateurs !')
            ->tag($this->tag);

        return $message;
    }

    private function inviteDatasAnalyst()
    {
        $message = (new MailMessage())
            ->subject('Invitation en attente')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name . ' vous invite à accéder au tableau de bord de la plateforme JeVeuxAider.gouv.fr')
            ->action("Voir l'invitation", $this->trackedUrl('/invitations/' . $this->invitation->token))
            ->line('En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support Utilisateurs !')
            ->tag($this->tag);

        return $message;
    }

    private function inviteBenevole()
    {
        $message = (new MailMessage())
            ->subject($this->invitation->user->profile->first_name . ' vous invite à créer un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name . ' vous invite à accéder à la plateforme JeVeuxAider.gouv.fr')
            ->action("Voir l'invitation", $this->trackedUrl('/invitations/' . $this->invitation->token))
            ->line("Une fois l'invitation acceptée, vous pourrez vous engager sur des missions de bénévolat partout en France.")
            ->line('En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support Utilisateurs !')
            ->tag($this->tag);

        return $message;
    }

    private function inviteDefault()
    {
        $message = (new MailMessage())
            ->subject($this->invitation->user->profile->first_name . ' vous invite à créer un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name . ' vous invite à accéder à la plateforme JeVeuxAider.gouv.fr.')
            ->action("Voir l'invitation", $this->trackedUrl('/invitations/' . $this->invitation->token))
            ->tag($this->tag);

        return $message;
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

    private function setTag()
    {
        switch ($this->invitation->role) {
            case 'responsable_organisation':
                $this->tag = 'app-invite-responsable-organization';
                break;
            case 'responsable_territoire':
                $this->tag = 'app-invite-responsable-territoire';
                break;
            case 'responsable_antenne':
                $this->tag = 'app-invite-responsable-antenne';
                break;
            case 'responsable_reseau':
                $this->tag = 'app-invite-responsable-reseau';
                break;
            case 'referent_departemental':
                $this->tag = 'app-invite-referent-departemental';
                break;
            case 'referent_regional':
                $this->tag = 'app-invite-referent-regional';
                break;
            case 'datas_analyst':
                $this->tag = 'app-invite-datas-analyst';
                break;
            case 'benevole':
                $this->tag = 'app-invite-benevole';
                break;
            default:
                $this->tag = 'app-invite-default';
                break;
        }
    }
}

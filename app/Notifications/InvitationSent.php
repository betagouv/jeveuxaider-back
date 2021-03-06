<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvitationSent extends Notification implements ShouldQueue
{
    use Queueable;

    public $invitation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
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
        $message = (new MailMessage)
            ->subject($this->invitation->user->profile->first_name.' de '.$this->invitation->invitable->name.' vous invite ?? cr??er un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name." vous invite ?? rejoindre la plateforme JeVeuxAider.gouv.fr afin de g??rer l'organisation ".$this->invitation->invitable->name)
            ->action("Voir l'invitation", url(config('app.front_url').'/invitations/'.$this->invitation->token))
            ->line('Une fois l???invitation accept??e, vous pourrez facilement publier des missions en vue de recruter des b??n??voles.')
            ->line('En cas de besoin, vous pouvez r??pondre ?? ce mail pour ??changer directement avec le support Utilisateurs !');

        return $message;
    }

    private function inviteResponsableTerritoire()
    {
        $message = (new MailMessage)
            ->subject($this->invitation->user->profile->first_name.' de '.$this->invitation->invitable->name.' vous invite ?? cr??er un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name.' vous invite ?? rejoindre la plateforme JeVeuxAider.gouv.fr afin de g??rer le territoire '.$this->invitation->invitable->name)
            ->action("Voir l'invitation", url(config('app.front_url').'/invitations/'.$this->invitation->token))
            ->line('Une fois l???invitation accept??e, vous pourrez facilement publier des missions en vue de recruter des b??n??voles.')
            ->line('En cas de besoin, vous pouvez r??pondre ?? ce mail pour ??changer directement avec le support Utilisateurs !');

        return $message;
    }

    private function inviteResponsableAntenne()
    {
        $message = (new MailMessage)
            ->subject($this->invitation->user->profile->first_name.' de '.$this->invitation->invitable->name.' vous invite ?? cr??er un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name." vous invite ?? cr??er un compte pour l'organisation ".$this->invitation->properties['antenne_name'].' sur JeVeuxAider.gouv.fr.')
            ->action("Voir l'invitation", url(config('app.front_url').'/invitations/'.$this->invitation->token))
            ->line('Une fois l???invitation accept??e, vous serez rattach?? au r??seau '.$this->invitation->invitable['name'].' et pourrez facilement publier des missions en vue de recruter des b??n??voles.')
            ->line('En cas de besoin, vous pouvez r??pondre ?? ce e-mail pour ??changer directement avec le support Utilisateurs !');

        return $message;
    }

    private function inviteResponsableReseau()
    {
        $message = (new MailMessage)
            ->subject($this->invitation->user->profile->first_name.' de '.$this->invitation->invitable->name.' vous invite ?? cr??er un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name.' vous invite ?? superviser le r??seau '.$this->invitation->invitable->name.' sur JeVeuxAider.gouv.fr')
            ->action("Voir l'invitation", url(config('app.front_url').'/invitations/'.$this->invitation->token))
            ->line("Une fois l'invitation accept??e, vous pourrez facilement piloter l'activit?? de votre r??seau associatif sur la plateforme JeVeuxAider.gouv.fr.")
            ->line('En cas de besoin, vous pouvez r??pondre ?? ce mail pour ??changer directement avec le support Utilisateurs !');

        return $message;
    }

    private function inviteReferentDepartemental()
    {
        $departmentNumber = $this->invitation->properties['referent_departemental'];
        $departmentName = config('taxonomies.departments.terms')[$departmentNumber];

        $message = (new MailMessage)
            ->subject($this->invitation->user->profile->first_name.' vous invite ?? cr??er un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name.' vous invite ?? devenir r??f??rent du d??partement '.$departmentName.' ('.$departmentNumber.') sur la plateforme JeVeuxAider.gouv.fr')
            ->action("Voir l'invitation", url(config('app.front_url').'/invitations/'.$this->invitation->token))
            ->line("Une fois l???invitation accept??e, vous pourrez facilement suivre l'activit?? de la R??serve Civique sur votre d??partement.")
            ->line('En cas de besoin, vous pouvez r??pondre ?? ce mail pour ??changer directement avec le support Utilisateurs !');

        return $message;
    }

    private function inviteReferentRegional()
    {
        $message = (new MailMessage)
            ->subject($this->invitation->user->profile->first_name.' vous invite ?? cr??er un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name.' vous invite ?? devenir r??f??rent de la r??gion '.$this->invitation->properties['referent_regional'].' sur la plateforme JeVeuxAider.gouv.fr')
            ->action("Voir l'invitation", url(config('app.front_url').'/invitations/'.$this->invitation->token))
            ->line("Une fois l???invitation accept??e, vous pourrez facilement suivre l'activit?? de la R??serve Civique sur votre r??gion.")
            ->line('En cas de besoin, vous pouvez r??pondre ?? ce mail pour ??changer directement avec le support Utilisateurs !');

        return $message;
    }

    private function inviteDatasAnalyst()
    {
        $message = (new MailMessage)
            ->subject('Invitation en attente')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name.' vous invite ?? acc??der au tableau de bord de la plateforme JeVeuxAider.gouv.fr')
            ->action("Voir l'invitation", url(config('app.front_url').'/invitations/'.$this->invitation->token))
            ->line('En cas de besoin, vous pouvez r??pondre ?? ce mail pour ??changer directement avec le support Utilisateurs !');

        return $message;
    }

    private function inviteBenevole()
    {
        $message = (new MailMessage)
            ->subject($this->invitation->user->profile->first_name.' vous invite ?? cr??er un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name.' vous invite ?? acc??der ?? la plateforme JeVeuxAider.gouv.fr')
            ->action("Voir l'invitation", url(config('app.front_url').'/invitations/'.$this->invitation->token))
            ->line("Une fois l'invitation accept??e, vous pourrez vous engager sur des missions de b??n??volat partout en France.")
            ->line('En cas de besoin, vous pouvez r??pondre ?? ce mail pour ??changer directement avec le support Utilisateurs !');

        return $message;
    }

    private function inviteDefault()
    {
        $message = (new MailMessage)
            ->subject($this->invitation->user->profile->first_name.' vous invite ?? cr??er un compte sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line($this->invitation->user->profile->full_name.' vous invite ?? acc??der ?? la plateforme JeVeuxAider.gouv.fr.')
            ->action("Voir l'invitation", url(config('app.front_url').'/invitations/'.$this->invitation->token));

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
}

<?php

namespace App\Notifications;

use App\Models\Message;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Structure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
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
        $message = (new MailMessage);
        $urlCTA = $this->message->conversation ? '/messages/'.$this->message->conversation->id : '/messages';

        if ($this->message->conversation->conversable::class == Participation::class) {
            $participation = $this->message->conversation->conversable;
            $fromOrga = false;
            if ($participation->mission->responsable_id == $this->message->from->profile->id) {
                $fromOrga = $participation->mission->structure;
            }
            if ($fromOrga) {
                $message->subject('Nouveau message de la part de '.$this->message->from->profile->first_name.' ('.$fromOrga->name.')')
                    ->tag('app-benevole-nouveau-message');
            } else {
                $message->subject('Nouveau message de la part de '.$this->message->from->profile->first_name)
                    ->tag('app-organisation-nouveau-message');
            }
            $message->greeting('Bonjour '.$notifiable->profile->first_name.',')
                ->line($this->message->from->profile->full_name.' a répondu à votre message concernant la mission "'.$participation->mission->name.'"')
                ->line('Vous pouvez échanger avec cette personne directement **via la messagerie** de JeVeuxAider.gouv.fr.');

            $message->action('Continuez la conversation', url(config('app.front_url').$urlCTA));
        }

        if ($this->message->conversation->conversable::class == Structure::class) {
            $structure = $this->message->conversation->conversable;

            $toResponsable = false;
            if ($structure->members->contains('id', $notifiable->id)) {
                $toResponsable = true;
            }

            if ($toResponsable) {
                $message->subject($this->message->from->profile->full_name.' souhaite en savoir plus sur votre organisation');
                $message->greeting('Bonjour '.$notifiable->profile->first_name.',')
                ->line($this->message->from->profile->full_name.' souhaite obtenir des informations complémentaires quant à votre organisation. C’est la personne référente sur votre département pour assurer la mise en ligne de missions de bénévolat.')
                ->line('Nous vous invitons à lui répondre dans les plus brefs délais, **via la messagerie** de JeVeuxAider.gouv.fr.')
                ->action('Consulter le message', url(config('app.front_url').$urlCTA))
                ->line('Pour tout complément d’information, vous pouvez contacter le support utilisateurs par retour de mail')
                ->line('Une bonne journée à vous,');
            } else {
                $message->subject('Le responsable de '.$structure->name.' vous a répondu !');
                $message->greeting('Bonjour '.$notifiable->profile->first_name.',')
                ->line($this->message->from->profile->full_name.', qui assure la gestion de l’organisation **'.$structure->name.'**, vous a apporté une réponse sur la messagerie de JeVeuxAider.gouv.fr.')
                ->action('Consulter le message', url(config('app.front_url').$urlCTA))
                ->line('Une bonne journée à vous,');
            }
        }

        if ($this->message->conversation->conversable::class == Mission::class) {
            $structure = $this->message->conversation->conversable->structure;

            $toResponsable = false;
            if ($structure->members->contains('id', $notifiable->id)) {
                $toResponsable = true;
            }

            if ($toResponsable) {
                $message->subject($this->message->from->profile->full_name.' souhaite en savoir plus sur votre mission');
                $message->greeting('Bonjour '.$notifiable->profile->first_name.',')
                ->line($this->message->from->profile->full_name.' souhaite obtenir des informations complémentaires quant à votre mission. C’est la personne référente sur votre département pour assurer la mise en ligne de missions de bénévolat.')
                ->line('Nous vous invitons à lui répondre dans les plus brefs délais, **via la messagerie** de JeVeuxAider.gouv.fr.')
                ->action('Consulter le message', url(config('app.front_url').$urlCTA))
                ->line('Pour tout complément d’information, vous pouvez contacter le support utilisateurs par retour de mail')
                ->line('Une bonne journée à vous,');
            } else {
                $message->subject('Le responsable de '.$structure->name.' vous a répondu !');
                $message->greeting('Bonjour '.$notifiable->profile->first_name.',')
                ->line($this->message->from->profile->full_name.', qui assure la gestion de l’organisation **'.$structure->name.'**, vous a apporté une réponse sur la messagerie de JeVeuxAider.gouv.fr.')
                ->action('Consulter le message', url(config('app.front_url').$urlCTA))
                ->line('Une bonne journée à vous,');
            }
        }

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

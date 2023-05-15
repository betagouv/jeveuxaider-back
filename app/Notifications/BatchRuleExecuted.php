<?php

namespace App\Notifications;

use App\Models\Participation;
use App\Models\Term;
use App\Models\User;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class BatchRuleExecuted extends Notification
{
    public $rule;
    public $itemsCount;
    public $user;

    public function __construct($rule, $user, $itemsCount)
    {
        $this->rule = $rule;
        $this->itemsCount = $itemsCount;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $from = config('app.env') != 'production' ? '['.config('app.env').'] JeVeuxAider.gouv.fr' : 'JeVeuxAider.gouv.fr';

        return (new SlackMessage)
            ->from($from)
            ->success()
            ->to('#' . config('services.slack.log_channel'))
            ->content('*'.$this->user->profile->full_name . '* a mis à jour *'. $this->itemsCount .' élément(s)*')
            ->attachment(function ($attachment) {
                $attachment
                    ->title($this->rule->name)
                    ->fields([
                        'Action' => config('taxonomies.rules.terms')[$this->rule->action_key],
                        'Valeur' => $this->getValue(),
                    ])
                    //->color('#BBBBBB')
                ;
            });
    }

    protected function getValue() {

        switch($this->rule->action_key){
            case 'mission_attach_tag':
                $term = Term::find($this->rule->action_value);
                return "$term->name #$term->id";
        }

        return $this->rule->action_value;
    }
}

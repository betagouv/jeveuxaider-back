@component('mail::message')
    @component('mail::components.headline', ['align' => 'center'])
        Votre mission est complète 💯
    @endcomponent
    @component('mail::components.paragraph', [
        'title' => 'La mission',
        'align' => 'center',
    ])
        {{ $mission->name }}
    @endcomponent
    @component('mail::components.space', ['height' => 24])
    @endcomponent
    @component('mail::components.paragraph')
       <p>Vous avez plusieurs options :</p>
       <ul>
            <li>Passer la mission au statut “Terminée” si vous avez reçu suffisamment de propositions. La mission sera dépubliée et les participations non traitées seront annulées.</li>
            <li>Mettre à jour les participations reçues. Une participation refusée = une place libérée. Vous pourrez ainsi continuer de recevoir des demandes de bénévoles. </li>
            <li>Augmenter le nombre de bénévoles recherchés pour continuer à recevoir des propositions d’aide.</li>
       </ul>
       <p>Quoi qu’il en soit, une action est nécessaire de votre part, en fonction de vos besoins. </p>
    @endcomponent
    @component('mail::button', ['url' => $url])
        Je mets à jour ma mission
    @endcomponent
    @component('mail::components.space', ['height' => 48])
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Pour information,'])
       Une mission est considérée comme complète si le nombre de bénévoles recherchés est égal aux nombres de participations en attente de traitement, en cours de validation ou validée.
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        Contactez le support en répondant à cet e-mail, nous sommes à votre disposition pour vous guider sur la plateforme.
    @endcomponent
@endcomponent

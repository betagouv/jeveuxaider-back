@component('mail::message')
    @component('mail::components.headline')
        Bonjour 👋🏻,
    @endcomponent
    @component('mail::components.paragraph')
        Votre mission <strong style="color: #1a1a1a; font-weight: 600;">{{ $mission->name }}</strong> est arrivée à échéance le {{ \Carbon\Carbon::parse($mission->end_date)->format('d/m/Y') }}. Deux solutions s’offrent à vous :
    @endcomponent
    @component('mail::components.paragraph')
        <ul>
            <li>Mettre à jour la date de fin si votre mission se poursuit,</li>
            <li>Valider/refuser les participations non traitées des bénévoles, puis passer votre mission au statut “Terminé”.</li>
        </ul>
    @endcomponent
    @component('mail::components.paragraph')
        Sans action de votre part, la mission sera automatiquement clôturée un mois après la date de fin, et l'ensemble des participations non traitées seront refusées.
    @endcomponent
    @component('mail::components.space', ['height' => 24])
    @endcomponent
    @component('mail::button', ['url' => $missionUrl])
        Mettre à jour ma mission
    @endcomponent
    @component('mail::components.space', ['height' => 24])
    @endcomponent
    @component('mail::components.tips', ['title' => 'Besoin d\'aide ?'])
        Contactez le support en répondant à cet e-mail, nous sommes à votre disposition pour vous guider sur la plateforme.
    @endcomponent
@endcomponent

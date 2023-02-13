@component('mail::message')
    @component('mail::components.headline')
        Votre organisation est en cours de validation ⏳
    @endcomponent
    @component('mail::components.paragraph')
        L’inscription de <strong>{{ $structure->name }}</strong> sur notre plateforme est en attente. Un référent départemental
        de
        JeVeuxAider.gouv.fr va étudier votre organisation pour valider qu’elle correspond bien aux critères définis dans le
        cadre de notre <a class="link" href="https://www.jeveuxaider.gouv.fr/charte-reserve-civique">Charte de la Réserve
            Civique ›</a>
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Bon à savoir :'])
        Dans le cadre de cette étude, le référent pourra être amené à prendre contact avec vous pour obtenir plus d’informations
        concernant l’activité de votre structure. Cette étape peut prendre jusqu’à 7 jours.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Ajoutez dès maintenant vos missions !'])
        Vous pouvez dès à présent proposer des missions de bénévolat. Elles seront visibles sur la plateforme dès que
        l’organisation aura été validée. De quoi trouver vos bénévoles au plus vite !
        @component('mail::components.space', ['height' => 25])
        @endcomponent
        @component('mail::button', ['url' => $url, 'align' => 'left'])
            Ajoutez vos missions
        @endcomponent
    @endcomponent
@endcomponent

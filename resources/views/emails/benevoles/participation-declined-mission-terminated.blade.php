@component('mail::message')
    @component('mail::components.headline')
        La mission est terminée 🥺
    @endcomponent
    @component('mail::components.paragraph')
        La mission proposée par <strong style="color: #1a1a1a; font-weight: 600;">{{ $structure->name }}</strong> à laquelle vous
        avez candidaté est terminée. Par conséquent, votre candidature a été automatiquement déclinée.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        <div>{{ $mission->name }}</div>
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <a class="link" href="{{ $url }}">Plus de détails ›</a>
    @endcomponent
    @component('mail::components.paragraph')
        P.S: Si vous avez réalisé la mission ou êtes en discussion avec le responsable de l’organisation, vous pouvez ignorer
        cet email.
    @endcomponent
    @component('mail::components.tips', ['title' => 'Ce n\'est que partie remise !'])
        Plus de 10 000 missions de bénévolat vous attendent sur JeVeuxAider.gouv.fr
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        @component('mail::button', ['url' => $urlSearch, 'align' => 'left'])
            Trouver une nouvelle mission
        @endcomponent
    @endcomponent
@endcomponent

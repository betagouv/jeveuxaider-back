@component('mail::message')
    @component('mail::components.headline')
        Votre mission est en cours de traitement ⏳
    @endcomponent
    @component('mail::components.paragraph')
        Elle n'est encore validée car nous avons besoin de plus d’informations. Afin d’y voir plus clair, votre référent
        départemental prendra prochainement contact avec vous.
    @endcomponent
    @component('mail::components.paragraph')
        En attendant, vous pouvez vérifier que votre mission est conforme aux critères de publication définis dans le cadre de notre <a class="link" href="{{ $urlCharte }}">Charte de la Réserve Civique ›</a> et la modifier si besoin.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        {{ $mission->name }}
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <a class="link" href="{{ $url }}">Plus de détails ›</a>
    @endcomponent
@endcomponent

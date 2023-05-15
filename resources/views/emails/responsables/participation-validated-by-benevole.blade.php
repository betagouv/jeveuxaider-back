@component('mail::message')
    @component('mail::components.headline')
        {{ $benevole->full_name }} vient de valider sa participation ✅
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        <div>{{ $mission->name }}</div>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Ce bénévole n\'a pas participé ?'])
        Il se peut que le bénévole se soit trompé. Mais pas de problème, vous pouvez toujours vous rendre sur votre espace et annuler sa participation
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        @component('mail::button', ['url' => $url, 'align' => 'left'])
            Annuler sa participation
        @endcomponent
    @endcomponent
@endcomponent

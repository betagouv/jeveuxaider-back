@component('mail::message')
    @component('mail::components.headline')
        {{ $benevole->full_name }} vient d'annuler sa participation 😕
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        <div>{{ $mission->name }}</div>
    @endcomponent
    @component('mail::components.card-message', [
        'title' => $benevole->full_name,
        'subtitle' => $reason ?? 'Bénévole',
    ])
        {{ $message ?? 'Pas de message' }}
    @endcomponent
    @component('mail::components.tips', ['title' => 'Pas de panique !'])
        Votre mission est toujours visible sur JeVeuxAider.gouv.fr. Nous faisons notre maximum pour vous trouver de nouveaux
        bénévoles.
    @endcomponent
@endcomponent

@component('mail::message')
    @component('mail::components.headline')
        Bonne nouvelle 🥳 !
    @endcomponent
    @component('mail::components.paragraph')
        Votre mission a été réactivée par un modérateur et elle est de nouveau visible dans la recherche 😉. De nouveaux bénévoles peuvent désormais s'y inscrire !
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        {{ $mission->name }}
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <a class="link" href="{{ $missionUrl }}">Voir la mission ›</a>
    @endcomponent
@endcomponent

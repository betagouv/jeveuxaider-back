@component('mail::message')
    @component('mail::components.headline')
        Votre participation a été déclinée 🥺
    @endcomponent
    @component('mail::components.paragraph')
        L’organisation <strong style="color: #1a1a1a; font-weight: 600;">{{ $structure->name }}</strong> a bien reçu votre
        candidature, malheureusement elle ne pourra pas vous accueillir pour cette mission de bénévolat.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        <div>{{ $mission->name }}</div>
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <a class="link" href="{{ $url }}">Plus de détails ›</a>
    @endcomponent
    @isset($message)
        @component('mail::components.card-message', [
            'title' => $responsable->first_name,
            'subtitle' => 'Responsable de mission chez ' . $structure->name,
        ])
            {{ $message }}
            @slot('footer')
                <span style="color: #5E5E5E; font-size: 19px; line-height: 22px; text-decoration: none;">Pour
                    plus d’informations, échangez avec {{ $responsable->first_name }} via la <a href="{{ $url }}"
                        style="color: #070191; text-decoration:  ">messagerie ›</a> </span>
                @component('mail::components.space', ['height' => 24])
                @endcomponent
            @endslot
        @endcomponent
    @endisset
    @component('mail::components.tips', ['title' => 'Ce n\'est que partie remise !'])
        Plus de 10 000 missions de bénévolat vous attendent sur JeVeuxAider.gouv.fr
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        @component('mail::button', ['url' => $urlCTA, 'align' => 'left'])
            Trouver une nouvelle mission
        @endcomponent
    @endcomponent
@endcomponent

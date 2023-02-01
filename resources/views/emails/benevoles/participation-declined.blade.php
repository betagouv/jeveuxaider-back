@component('mail::message')
    @component('mail::components.headline')
        Votre participation a été déclinée 🥺
    @endcomponent
    @component('mail::components.paragraph')
        L’organisation <strong style="color: #1a1a1a; font-weight: 600;">{{ $structure->name }}</strong> a bien reçu votre
        candidature, malheureusement elle ne pourra pas vous accueillir pour cette mission de bénévolat.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        {{ $mission->name }}<br />
        <a class="link" href="{{ $url }}" target="_blank">Plus de détails ›</a>
    @endcomponent
    @component('mail::components.card-message', [
        'title' => $responsable->first_name,
        'subtitle' => 'Responsable de mission chez ' . $structure->name,
    ])
        {{ $message }}
        @slot('footer')
            <font face="'Source Sans Pro', sans-serif" color="#5E5E5E"
                style="font-size: 19px; line-height: 22px; text-decoration: none;">
                <span
                    style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #5E5E5E; font-size: 19px; line-height: 22px; text-decoration: none;">Pour
                    plus d’informations, échangez avec {{ $responsable->first_name }} via la <a href="{{ $url }}"
                        style="color: #070191; text-decoration:  ">messagerie ›</a> </span>
            </font>
            <div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
        @endslot
    @endcomponent
    @component('mail::components.tips', ['title' => 'Ce n\'est que partie remise !'])
        Plus de 10 000 missions de bénévolat vous attendent sur JeVeuxAider.gouv.fr
        <div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
        @component('mail::button', ['url' => $url, 'align' => 'left'])
            Trouver une nouvelle mission
        @endcomponent
    @endcomponent
@endcomponent

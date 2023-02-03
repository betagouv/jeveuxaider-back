@component('mail::message')
    @component('mail::components.headline')
        Votre mission a dû être annulée 🥺
    @endcomponent
    @component('mail::components.paragraph')
        Nous sommes désolés de vous informer que <strong
            style="color: #1a1a1a; font-weight: 600;">{{ $structure->name }}</strong> a annulé la mission à laquelle vous deviez
        participer. Nous comprenons votre déception et vous remercions pour votre engagement.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        <div>{{ $mission->name }}</div>
        <div style="height: 10px; line-height: 10px; font-size: 10px;">&nbsp;</div>
        <a class="link" href="{{ $url }}">Plus de détails ›</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Ce n\'est que partie remise !'])
        Plus de 10 000 missions de bénévolat vous attendent sur JeVeuxAider.gouv.fr
        <div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
        @component('mail::button', ['url' => $url, 'align' => 'left'])
            Trouver une nouvelle mission
        @endcomponent
    @endcomponent
@endcomponent

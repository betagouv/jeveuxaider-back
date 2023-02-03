@component('mail::message')
    @component('mail::components.headline')
        Votre participation est validée 👍
    @endcomponent
    @component('mail::components.paragraph')
        {{ $responsable->first_name }}, le responsable de la mission, prendra contact avec vous dans les prochains
        jours (via la messagerie de JeVeuxAider.gouv.fr ou en direct grâce aux coordonnées de votre profil).
    @endcomponent
    @component('mail::components.paragraph')
        Des questions ? Écrivez à {{ $responsable->first_name }} via la <a class="link" href="{{ $url }}">messagerie
            ›</a>
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        <div>{{ $mission->name }}</div>
        <div style="height: 10px; line-height: 10px; font-size: 10px;">&nbsp;</div>
        <a class="link" href="{{ $url }}">Plus de détails ›</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Vous n\'êtes plus disponible ?'])
        Pensez à prévenir le responsable au plus vite pour qu’il puisse trouver un autre bénévole.
        <div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
        @component('mail::button', ['url' => $url, 'align' => 'left'])
            Informer le responsable
        @endcomponent
    @endcomponent
@endcomponent

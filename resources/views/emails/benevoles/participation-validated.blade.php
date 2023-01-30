@component('mail::message')
    @component('mail::components.headline')
        Votre participation est validée 👍
    @endcomponent
    @component('mail::components.paragraph')
        {{ $responsable->first_name }}, le responsable de la mission, prendra contact avec vous dans les prochains
        jours (via la messagerie de JeVeuxAider.gouv.fr ou en direct grâce aux coordonnées de votre profil).
    @endcomponent
    @component('mail::components.paragraph')
        Des questions ? Écrivez à {{ $responsable->first_name }} via la <a class="link" href="{{ $url }}"
            target="_blank">messagerie ›</a>
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        {{ $mission->name }}<br />
        <a class="link" href="{{ $url }}" target="_blank">Plus de détails ›</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Vous n\'êtes plus disponible ?'])
        Pensez à prévenir le responsable au plus vite pour qu’il puisse trouver un autre bénévole.
        <div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
        @component('mail::button', ['url' => $url, 'align' => 'left'])
            Informer le responsable
        @endcomponent
    @endcomponent
@endcomponent

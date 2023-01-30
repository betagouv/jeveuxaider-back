@component('mail::message')
    @component('mail::components.headline')
        Votre participation est en attente de validation ⏳
    @endcomponent
    @component('mail::components.paragraph')
        Le responsable de la mission traite votre demande et vous contactera prochainement pour échanger avec vous par
        téléphone, par e-mail ou via <a class="link" href="{{ $url }}" target="_blank">messagerie ›</a>
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Un conseil'])
        Gardez un oeil sur votre messagerie ! Une réponse rapide est toujours appréciée et permettra au responsable d’évaluer
        votre participation au plus vite.
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

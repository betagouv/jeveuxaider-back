@component('mail::message')
    @component('mail::components.headline')
        Votre participation est en cours de traitement ⏳
    @endcomponent
    @component('mail::components.paragraph')
        Le responsable de la mission traite votre demande et vous contactera prochainement pour échanger avec vous par
        téléphone, par e-mail ou via <a class="link" href="{{ $url }}">messagerie ›</a>
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Un conseil'])
        Gardez un oeil sur votre messagerie ! Une réponse rapide est toujours appréciée et permettra au responsable d’évaluer
        votre participation au plus vite.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        <div>{{ $mission->name }}</div>
        @component('mail::components.space', ['height' => 10])@endcomponent
        <a class="link" href="{{ $url }}">Plus de détails ›</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Vous n\'êtes plus disponible ?'])
        Pensez à prévenir le responsable au plus vite pour qu’il puisse trouver un autre bénévole.
        @component('mail::components.space', ['height' => 24])@endcomponent
        @component('mail::button', ['url' => $url, 'align' => 'left'])
            Informer le responsable
        @endcomponent
    @endcomponent
@endcomponent

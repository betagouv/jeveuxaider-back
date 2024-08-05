@component('mail::message')
    @component('mail::components.headline')
        Votre demande de participation a bien été enregistrée 🔖
    @endcomponent
    @component('mail::components.paragraph')
        Le responsable de la mission a bien reçu votre demande et vous contactera prochainement pour échanger avec vous par
        téléphone, par e-mail ou via la <a class="link" href="{{ $url }}">messagerie ›</a>
    @endcomponent
    @if($profileIsIncomplete)
        @component('mail::components.paragraph', ['title' => 'Deux conseils pour faciliter vos échanges avec l’organisation'])
            <p><strong>Gardez un oeil sur votre messagerie !</strong> Une réponse rapide est toujours appréciée et permettra au responsable d’évaluer votre participation au plus vite.</p>
            <p><strong>Complétez votre profil !</strong>  Un profil complet aide les organisations à mieux vous connaître et comprendre ce que vous cherchez.</p>
        @endcomponent
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <p>
            <img src="https://www.jeveuxaider.gouv.fr/images/profiles/badge-identity.svg" width="100%" style="height: 200px; width: auto !important;" />
            @component('mail::button', ['url' => $profileUrl, 'align' => 'center'])
                Compléter mon profil
            @endcomponent
        </p>
        @component('mail::components.space', ['height' => 50])
        @endcomponent
    @else
        @component('mail::components.paragraph', ['title' => 'Un conseil'])
            <p><strong>Gardez un oeil sur votre messagerie !</strong> Une réponse rapide est toujours appréciée et permettra au responsable d’évaluer votre participation au plus vite.</p>
        @endcomponent
    @endif
    @component('mail::components.divider', ['spaceTop' => 8, 'spaceBottom' => 32])
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        <div>{{ $mission->name }}</div>
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <a class="link" href="{{ $url }}">Plus de détails ›</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Vous n\'êtes plus disponible ?'])
        Pensez à prévenir le responsable au plus vite pour qu’il puisse trouver un autre bénévole.
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        @component('mail::button', ['url' => $url, 'align' => 'left'])
            Informer le responsable
        @endcomponent
    @endcomponent
@endcomponent

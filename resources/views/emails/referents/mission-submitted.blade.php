@component('mail::message')
    @component('mail::components.headline')
        Une nouvelle mission attend votre validation ! ⏳
    @endcomponent
    @component('mail::components.paragraph')
        L’organisation <strong>{{ $mission->structure->name }}</strong> propose une nouvelle mission :
        <strong>{{ $mission->name }}</strong>.
    @endcomponent
    @component('mail::components.paragraph')
        Vous pouvez dès à présent vous connecter sur votre espace personnel pour l’étudier.
    @endcomponent
    @component('mail::button', ['url' => $url])
        Voir la mission
    @endcomponent
    @component('mail::components.tips', ['title' => 'Nous comptons sur vous'])
        Votre réactivité est essentielle pour l’organisation, car tant que la mission n’est pas validée, elle ne peut pas
        démarrer la recherche de bénévoles.
    @endcomponent
@endcomponent

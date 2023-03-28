@component('mail::message')
    @component('mail::components.headline')
        Une nouvelle organisation attend votre validation ! ⏳
    @endcomponent
    @component('mail::components.paragraph')
        L’organisation <strong>{{ $structure->name }}</strong> vient de s’inscrire dans votre département et n’attend plus que
        votre validation pour publier des missions.
    @endcomponent
    @component('mail::button', ['url' => $url])
        Voir l’organisation
    @endcomponent
    @component('mail::components.tips', ['title' => 'Nous avons besoin de vous'])
        Pour rappel, tant que vous n’avez pas validé ou signalé l’organisation, elle ne pourra pas publier de missions pour
        commencer à rechercher ses bénévoles. Nous comptons sur vous pour répondre rapidement 🙂
    @endcomponent
@endcomponent

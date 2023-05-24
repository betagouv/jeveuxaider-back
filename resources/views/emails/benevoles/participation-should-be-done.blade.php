@component('mail::message')
    @component('mail::components.headline')
        {{ $benevole->first_name }}, avez-vous réalisé votre mission ? 🙌
    @endcomponent
    @component('mail::components.paragraph')
        Il y a quelques temps, vous aviez proposé votre aide pour la mission <strong style="color: #1a1a1a; font-weight: 600;">{{ $mission->name }}</strong> de {{ $organisation->name }}.
    @endcomponent
    @component('mail::components.paragraph')
        On a besoin de savoir si vous avez participé à cette mission ou non.
    @endcomponent
    @component('mail::components.paragraph')
        Rendez-vous sur votre espace pour mettre à jour le statut de votre participation. Vous pourrez la valider (💪🏻) ou l’annuler si elle n’est plus d’actualité (😢).
    @endcomponent
    @component('mail::components.paragraph')
        Si la mission n'a pas encore ou lieu ou n'est pas encore terminée, nous vous invitons à le faire plus tard.
    @endcomponent
    @component('mail::components.space', ['height' => 24])
    @endcomponent
    @component('mail::button', ['url' => $url])
        Mettre à jour ma participation
    @endcomponent
    @component('mail::components.space', ['height' => 48])
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Pourquoi mettre à jour le statut de votre participation ?'])
        En tant que plateforme publique du bénévolat, nous avons besoin de connaître le nombre de bénévoles qui se sont réellement engagés par notre intermédiaire. Alors, on compte sur vous, ça se fait en 2 clics !
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent
@endcomponent

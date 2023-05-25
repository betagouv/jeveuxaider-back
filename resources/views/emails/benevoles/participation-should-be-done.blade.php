@component('mail::message')
    @component('mail::components.headline')
        {{ $benevole->first_name }}, avez-vous réalisé votre mission ? 🙌
    @endcomponent
    @component('mail::components.paragraph')
        Il y a quelques temps, vous avez proposé votre aide pour la mission <strong style="color: #1a1a1a; font-weight: 600;">{{ $mission->name }}</strong> de {{ $organisation->name }}.
    @endcomponent
    @component('mail::components.paragraph')
        On a besoin de savoir si vous avez participé à cette mission ou non.
    @endcomponent
    @component('mail::components.paragraph')
        Rendez-vous sur votre espace pour mettre à jour le statut de votre participation. Vous pourrez la valider ou l’annuler si elle n’est plus d’actualité.
    @endcomponent
    @component('mail::components.paragraph')
        Si la mission n'a pas encore eu lieu ou n'est pas encore terminée, nous vous invitons à le faire plus tard.
    @endcomponent
    @component('mail::components.space', ['height' => 24])
    @endcomponent
    @component('mail::button', ['url' => $url])
        Mettre à jour ma participation
    @endcomponent
    @component('mail::components.space', ['height' => 48])
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Pourquoi mettre à jour votre statut de participation ?'])
        Pour nous aider à mieux comprendre votre usage de la plateforme et assurer un suivi de l’engagement des bénévoles. Un petit clic de bénévole, un grand pas pour <a class="link" href="https://www.jeveuxaider.gouv.fr/">JeVeuxAider.gouv.fr</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent
@endcomponent

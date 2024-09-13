@component('mail::message')
    @component('mail::components.headline')
        🙌 Bonjour {{ $notifiable->profile->first_name }},
    @endcomponent
    @component('mail::components.paragraph')
        Ça y est ! Vous pouvez proposer votre aide pour la mission : <a class="link" href="{{ $url }}">{{ $mission->name }}</a>
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'À vous de jouer !'])
        Si la mission vous plaît toujours et que vous êtes disponible, proposez votre aide à l’organisation !
    @endcomponent
    @component('mail::button', ['url' => $url])
        Voir la mission
    @endcomponent
    @component('mail::components.space', ['height' => 32])
    @endcomponent
    @component('mail::components.tips', ['title' => 'Bonne pratique'])
        <p>Ne l’oubliez pas, lorsque vous proposez votre aide sur une mission, les organisations comptent sur vous.</p>
        <p style="font-weight: 800;">Si vous ne pouvez plus y participer, il suffit de les prévenir à l’aide de la <a class="link" href="{{ $urlMessages }}">messagerie</a>.</p>
    @endcomponent
    @component('mail::components.divider', ['spaceTop' => 32, 'spaceBottom' => 32])
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Pourquoi vous recevez cet email ?'])
        Vous aviez manisfesté votre intérêt pour cette mission et demandé à être notifié s’il était à nouveau possible de proposer son aide. Vous ne recevrez plus d’email à ce sujet :)
    @endcomponent
@endcomponent

@component('mail::message')
    @component('mail::components.headline')
        Une mission de bénévolat avec {{ $user->profile->first_name }}, ça vous tente ?
    @endcomponent
    @component('mail::components.paragraph')
        {{ htmlentities($user->profile->first_name) }} a proposé son aide pour une mission de bénévolat sur JeVeuxAider.gouv.fr, la plateforme
        publique du bénévolat, et aimerait la faire avec vous.
    @endcomponent
    @component('mail::components.paragraph')
        <div style="text-align: center; font-weight: bold; color: #000000">
            Alors, prêt à passer à l’action ?
        </div>
    @endcomponent
    @component('mail::button', ['url' => $url])
        Découvrir la mission
    @endcomponent
    @component('mail::components.space', ['height' => 24])
    @endcomponent
    @component('mail::components.card-mission', ['mission' => $mission, 'url' => $url])
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent
@endcomponent

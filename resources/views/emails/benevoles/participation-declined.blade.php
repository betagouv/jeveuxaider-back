@component('mail::message')
    @component('mail::components.headline', ['align' => 'center'])
        Votre participation a été déclinée 🥺 …<br>
        @slot('subtitle')
            … mais, à la fin de ce mail, on vous propose d'autres missions qui pourraient vous intéresser 👀
        @endslot
    @endcomponent
    @component('mail::components.divider', ['spaceTop' => 0, 'spaceBottom' => 33])
    @endcomponent
    @component('mail::components.paragraph')
        <p>L’organisation <strong style="color: #1a1a1a; font-weight: 600;">{{ $structure->name }}</strong> a bien reçu votre
        candidature, malheureusement elle ne pourra pas vous accueillir pour cette mission de bénévolat :</p>
        <p><a href="{{ $urlMission }}" class="link">{{ $mission->name }}</a></p>
    @endcomponent
    @isset($message)
        @component('mail::components.card-message', [
            'title' => 'Responsable de mission',
            'subtitle' => $structure->name,
            'spaceTop' => 0,
        ])
            {{ $message }}
            @slot('footer')
                <span style="color: #5E5E5E; font-size: 19px; line-height: 22px;"><a class="link" href="{{ $url }}">Plus d’informations dans la messagerie</a> </span>
                @component('mail::components.space', ['height' => 24])
                @endcomponent
            @endslot
        @endcomponent
    @endisset
    @if ($reason === 'no_response')
        @component('mail::components.tips', ['spaceTop' => 32])
            Ne l’oubliez pas, lorsque vous proposez votre aide sur une mission, les organisations comptent sur vous.
            @component('mail::components.space', ['height' => 24])
            @endcomponent
            <strong style="color: #1a1a1a; font-weight: 600;">Si vous ne pouvez plus y participer, il suffit de les prévenir à l’aide de la <a class="link" href="{{ $url }}">messagerie</a></strong>.
        @endcomponent
    @endif
    @component('mail::components.divider', ['spaceTop' => 32, 'spaceBottom' => 32])
    @endcomponent
    @component('mail::components.paragraph', ['title' => "Ce n'est que partie remise !"])
        Les organisations ont toujours besoin de bénévoles. Jetez un oeil au <a class="link" href="{{ $urlQuiz }}">quiz du bénévolat</a>, et découvrez une sélection personnalisée de missions 🎯
    @endcomponent
    @component('mail::components.space', ['height' => 8])
    @endcomponent
    @component('mail::button', ['url' => $urlQuiz])
        Répondre à notre quiz
    @endcomponent
    @component('mail::components.space', ['height' => 32])
    @endcomponent
    @component('mail::components.quiz', ['url' => $urlQuiz])
    @endcomponent
@endcomponent

@component('mail::message')
    @component('mail::components.headline')
        Votre mission a dû être annulée 🥺
    @endcomponent
    @component('mail::components.paragraph')
        Nous sommes désolés de vous informer que <strong
            style="color: #1a1a1a; font-weight: 600;">{{ $structure->name }}</strong> a annulé la mission à laquelle vous deviez
        participer. Nous comprenons votre déception et vous remercions pour votre engagement.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        <div>{{ $mission->name }}</div>
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <a class="link" href="{{ $url }}">Plus de détails ›</a>
    @endcomponent
    @component('mail::components.divider', ['spaceTop' => 20, 'spaceBottom' => 32])
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

@component('mail::message')
    @component('mail::components.headline', ['align' => 'center'])
        @if ($participationsCount > 1)
            {{ $participationsCount }} bénévoles souhaitent vous aider 🙌
        @else
            1 bénévole souhaite vous aider 🙌
        @endif
    @endcomponent
    @component('mail::components.space', ['height' => 24])
    @endcomponent
    @component('mail::components.card')
        <div style="display: block; max-width: 100px;">
            <img src="{{ config('app.front_url') }}/images/mail/users.jpg" alt="img" width="100" border="0"
                style="display: block; width: 100px; border-radius:100px">
        </div>
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        <p style="font-size: 22px; line-height: 32px; text-decoration: none;">
            🏀 La balle est dans votre camp.<br>
            <strong>En cours de traitement</strong>, <strong>Validé</strong> ou <strong>Refusé</strong>
            c'est vous qui choisissez.
        </p>
        @component('mail::components.space', ['height' => 12])
        @endcomponent
        @component('mail::button', ['url' => $url])
            @if ($participationsCount > 1)
                Répondre aux bénévoles
            @else
                Répondre au bénévole
            @endif
        @endcomponent
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        <p style="color: #8c8c8c; font-size: 19px!important; line-height: 23px; padding: 0 24px;">
            Plus vous êtes réactifs,<br>
            plus vous augmentez votre score de visibilité 🚀
        </p>
    @endcomponent
@endcomponent

@component('mail::message')
    @component('mail::components.headline', ['align' => 'center'])
        {{ $benevole->first_name }} attend votre réponse ⌛
    @endcomponent
    @component('mail::components.paragraph', [
        'title' => $benevole->first_name . ' vous propose sa participation sur la mission',
        'align' => 'center',
    ])
        {{ $mission->name }}
    @endcomponent
    @component('mail::components.space', ['height' => 24])
    @endcomponent
    @component('mail::components.card')
        <div style="display: block; max-width: 100px;">
            <img src="https://www.jeveuxaider.gouv.fr/images/mail/user.jpg" alt="img" width="100" border="0"
                style="display: block; width: 100px; border-radius:100px">
        </div>
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        <p style="color: #1a1a1a; font-size: 30px; line-height: 32px; font-weight: 600; text-decoration: none;">
            {{ $benevole->full_name }}</p>
        @component('mail::components.space', ['height' => 12])
        @endcomponent
        @component('mail::button', ['url' => $url])
            Répondre au bénévole
        @endcomponent
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        <p style="color: #5E5E5E; font-size: 19px; line-height: 28px; text-decoration: none; padding: 0 24px;">Nous vous
            conseillons de ne pas
            attendre plus d’une semaine pour apporter une réponse. Si vous répondez rapidement, le bénévole sera d’autant plus
            motivé !
        </p>
    @endcomponent
    @if($showInfos)
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        <p>Voici ses coordonnées :</p>
        <p>{{ $benevole->full_name }}<br>{{ $benevole->mobile }}<br>{{ $benevole->email }}</p>
    @endif
@endcomponent

@component('mail::message')
    @component('mail::components.headline', ['align' => 'center'])
        Votre mission est presque complète 😅
    @endcomponent
    @component('mail::components.paragraph', [
        'title' => 'La mission',
        'align' => 'center',
    ])
        {{ $mission->name }}
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
        <p style="color: #1a1a1a; font-size: 30px; line-height: 32px; font-weight: 600; text-decoration: none;">
            Plus qu'une place disponible
        </p>
        @component('mail::components.space', ['height' => 12])
        @endcomponent
        <p style="color: #5E5E5E; font-size: 19px; line-height: 28px; text-decoration: none; padding: 0 24px;">
            Les bénévoles ne pourront bientôt plus y candidater.
            Vous souhaitez augmenter la capacité de votre mission ?
        </p>
        @component('mail::button', ['url' => $url])
            Augmenter le nombre de places
        @endcomponent
        @component('mail::components.space', ['height' => 24])
        @endcomponent
    @endcomponent
@endcomponent

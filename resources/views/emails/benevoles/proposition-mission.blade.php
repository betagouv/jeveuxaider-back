@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->first_name }} 👋🏻,
    @endcomponent
    @component('mail::components.paragraph')
        L’organisation {{ $structure->name }} vous propose une nouvelle mission de bénévolat dans le domaine d'action <strong>{{ $domaine->name }}</strong>.
    @endcomponent
    @component('mail::components.paragraph')
        Votre profil correspond à celui des bénévoles recherchés.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'La mission'])
        {{ $mission->name }}
    @endcomponent
    @component('mail::button', ['url' => $url])
        Voir la mission
    @endcomponent
    @component('mail::components.space', ['height' => 48])
    @endcomponent
    @component('mail::components.paragraph')
        Nous comptons sur vous pour faire vivre l’engagement. Merci !
    @endcomponent
    @component('mail::components.tips', ['title' => 'Vous ne voulez plus recevoir des propositions de mission de la part des organisations ?'])
        <p>Vous pouvez désactiver ce paramètre sur votre profil, dans les préférences de communication.</p>
        <p><a class="link" href="{{ $urlProfilePreferences }}">Modifier mes préférences ›</a></p>
    @endcomponent
@endcomponent
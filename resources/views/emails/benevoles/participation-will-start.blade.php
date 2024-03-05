@component('mail::message')
    @component('mail::components.headline')
        C’est bientôt le début de la mission !
    @endcomponent
    @component('mail::components.paragraph')
        L’organisation <strong style="color: #1a1a1a; font-weight: 600;">{{ $organisation->name }}</strong> compte sur vous pour honorer votre engagement !
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Votre mission'])
        <div>{{ $mission->name }}</div>
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <a class="link" href="{{ $url }}">Plus de détails ›</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Vous avez des questions sur la mission ?'])
        N’hésitez pas à contacter directement le responsable de l’organisation.
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        @component('mail::button', ['url' => $url, 'align' => 'left'])
            Contacter le responsable
        @endcomponent
    @endcomponent
@endcomponent

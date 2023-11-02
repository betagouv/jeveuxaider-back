@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->first_name }},
    @endcomponent
    @component('mail::components.paragraph')
        La ressource « <strong>{{ $document->title }}</strong> » vient d'être uploadée dans votre tableau de bord.
    @endcomponent
    @if (!empty($document->description))
        @component('mail::components.card-message', [
            'title' => 'Caroline & Sophie',
            'subtitle' => 'Support JVA',
        ])
            {{ $document->description }}
        @endcomponent
    @endif
    @component('mail::components.space', ['height' => 48])
    @endcomponent
    @component('mail::button', ['url' => $url])
        Accéder à mes ressources
    @endcomponent
@endcomponent

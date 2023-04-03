@component('mail::message')
    @component('mail::components.headline')
        {{ $from->first_name }} vous a envoyé un message 💬
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Au sujet de la mission'])
        {{ $mission->name }}
    @endcomponent
    @if ($isFromResponsable)
        @component('mail::components.paragraph')
            {{ $from->first_name }}, responsable de mission chez <strong
                style="color: #1a1a1a; font-weight: 600;">{{ $structure->name }}</strong> vous a envoyé un message. Répondez lui
            au plus vite.
        @endcomponent
        @component('mail::button', ['url' => $url])
            Voir le message
        @endcomponent
    @else
        @component('mail::components.card-message', [
            'title' => $from->first_name,
            'subtitle' => 'Bénévole',
        ])
            {{ $message->content }}
            @slot('footer')
                @component('mail::button', ['url' => $url, 'align' => 'left'])
                    Répondre au bénévole
                @endcomponent
                @component('mail::components.space', ['height' => 24])
                @endcomponent
            @endslot
        @endcomponent
    @endif
    @component('mail::components.tips', [
        'title' => $isFromResponsable ? 'Ne le laissez pas sans réponse !' : 'Les petites astuces',
    ])
        @if ($isFromResponsable)
            Un retour rapide de votre part est toujours très apprécié de la part des responsables de mission, et décuple les
            possibilités d’engagement.
        @else
            Plus vous êtes réactif avec vos bénévoles et plus vos missions seront valorisées !
        @endif
    @endcomponent
@endcomponent

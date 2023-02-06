@component('mail::message')
    @component('mail::components.headline')
        {{ $from->first_name }} vous a envoyé un message 💬
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Au sujet de la mission'])
        {{ $mission->name }}
    @endcomponent
    @component('mail::components.card-message', [
        'title' => $from->first_name,
        'subtitle' => $isFromResponsable ? 'Responsable de mission chez ' . $structure->name : 'Bénévole',
    ])
        {{ $message->content }}
        @slot('footer')
            @component('mail::button', ['url' => $url, 'align' => 'left'])
                Répondre au message
            @endcomponent
            @component('mail::components.space', ['height' => 24])
            @endcomponent
            @if (!$isFromResponsable)
                <span style="color: #5E5E5E; font-size: 19px; line-height: 22px; text-decoration: none;">
                    Contactez directement le bénévole grâce aux informations présentes sur son profil, ou bien tout simplement
                    via la <a href="{{ $url }}" style="color: #070191; text-decoration:  ">messagerie ›</a>
                </span>
                @component('mail::components.space', ['height' => 24])
                @endcomponent
            @endif
        @endslot
    @endcomponent
    @component('mail::components.tips', [
        'title' => $isFromResponsable ? 'Ne le laissez pas sans réponse !' : '😉 Les petites astuces',
    ])
        @if ($isFromResponsable)
            Un retour rapide de votre part est toujours très apprécié de la part des responsables de mission, et décuple les
            possibilités d’engagement.
        @else
            Plus vous êtes réactif avec vos bénévoles et plus vos missions seront valorisées !
        @endif
    @endcomponent
@endcomponent

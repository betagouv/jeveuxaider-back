@component('mail::message')
    @component('mail::components.headline')
        {{ $from->first_name }} vous a envoyé un message 💬
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Au sujet de la mission'])
        {{ $mission->name }}
    @endcomponent
    @component('mail::components.card-message', [
        'title' => $from->first_name,
        'subtitle' => $isFromResponsable ? 'Responsable chez ' . $structure->name : 'Référent',
    ])
        {{ $message->content }}
        @slot('footer')
            @component('mail::button', ['url' => $url, 'align' => 'left'])
                Répondre au message
            @endcomponent
            <div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
        @endslot
    @endcomponent
    @component('mail::components.tips', ['title' => 'Ne le laissez pas sans réponse !'])
        @if (!$isFromResponsable)
            Un retour rapide de votre part est toujours très apprécié de la part des responsables d'organisation, et décuple les
            possibilités d’engagement.
        @else
            Un retour rapide de votre part est toujours très apprécié de la part des référents, et décuple les
            possibilités d’engagement.
        @endif
    @endcomponent
@endcomponent

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
            <div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
            @if (!$isFromResponsable)
                <font face="'Source Sans Pro', sans-serif" color="#5E5E5E"
                    style="font-size: 19px; line-height: 22px; text-decoration: none;">
                    <span
                        style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #5E5E5E; font-size: 19px; line-height: 22px; text-decoration: none;">
                        Contactez directement le bénévole grâce aux informations présentes sur son profil, ou bien tout simplement
                        via la <a href="{{ $url }}" style="color: #070191; text-decoration:  ">messagerie ›</a>
                    </span>
                </font>
                <div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
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

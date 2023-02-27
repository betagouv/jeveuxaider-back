<?php $showActions = true; ?>
@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->first_name }},
    @endcomponent
    @component('mail::components.paragraph')
        <p>Voici le récapitulatif des actions sur vos missions au cours des dernières 24h.</p>
        <ul>
            @if ($variables['newParticipationsCount'] == 0)
                <li>🌟 Aucune nouvelle demande de participation</li>
            @elseif($variables['newParticipationsCount'] == 1)
                <li>🌟 {{ $variables['newParticipationsCount'] }} bénévole a indiqué qu'il souhaitait participer à l'une de vos
                    missions</li>
            @else
                <li>🌟 {{ $variables['newParticipationsCount'] }} bénévoles ont indiqué qu'ils souhaitaient participer à l'une
                    de vos missions</li>
            @endif
            @if ($variables['newMessagesCount'] == 0)
                <li>💌 Aucun nouveau message</li>
            @elseif($variables['newMessagesCount'] == 1)
                <li>💌 Vous avez reçu {{ $variables['newMessagesCount'] }} nouveau message</li>
            @else
                <li>💌 Vous avez reçu {{ $variables['newMessagesCount'] }} nouveaux messages</li>
            @endif
        </ul>
    @endcomponent
    @if ($showActions)
        @component('mail::components.paragraph')
            <p>À vous de prendre le relais ! Rendez-vous sur votre compte JeVeuxAider.gouv.org pour tenir votre compte à jour :
            </p>
            <ul>
                @if ($variables['participationsWaitingCount'] == 0)
                    <li>🧐 Aucune demande de participation en attente de réponse de votre part</li>
                @elseif($variables['participationsWaitingCount'] == 1)
                    <li>🧐 {{ $variables['participationsWaitingCount'] }} demande de participation est en attente de réponse de
                        votre part</li>
                @else
                    <li>🧐 {{ $variables['participationsWaitingCount'] }} demandes de participation sont en attente de réponse
                        de votre part</li>
                @endif
                @if ($variables['conversationsUnreadCount'] == 0)
                    <li>📨 Aucun message marqué comme "non lu"</li>
                @elseif($variables['conversationsUnreadCount'] == 1)
                    <li>📨 {{ $variables['conversationsUnreadCount'] }} message est marqué comme "non lu"</li>
                @else
                    <li>📨 {{ $variables['conversationsUnreadCount'] }} messages sont marqués comme "non lus"</li>
                @endif
            </ul>
        @endcomponent
    @endif
    @component('mail::components.paragraph')
        Gardez à l’esprit que les associations qui répondent vite sont valorisées ; et que les bénévoles privilégient les
        missions pour lesquelles ils ont une réponse rapide !
    @endcomponent
    @component('mail::components.space', ['height' => 33])@endcomponent
    @component('mail::button', ['url' => $url])
        Je réponds aux bénévoles
    @endcomponent
    @component('mail::components.space', ['height' => 33])@endcomponent
    @component('mail::components.paragraph')
        PS : Vous avez des questions ? N’hésitez pas à nous répondre par retour de mail, nous sommes toujours disponibles
        pour vous.
    @endcomponent
@endcomponent

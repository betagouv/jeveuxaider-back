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
                <li>🌟 <strong>{{ $variables['newParticipationsCount'] }} bénévole</strong> a proposé son aide pour vos
                    missions</li>
            @else
                <li>🌟 <strong>{{ $variables['newParticipationsCount'] }} bénévoles</strong> ont proposé leur aide pour vos missions</li>
            @endif
            @if ($variables['newMessagesCount'] == 0)
                <li>💌 Aucun nouveau message</li>
            @elseif($variables['newMessagesCount'] == 1)
                <li>💌 Vous avez reçu <strong>{{ $variables['newMessagesCount'] }} nouveau message</strong></li>
            @else
                <li>💌 Vous avez reçu <strong>{{ $variables['newMessagesCount'] }} nouveaux messages</strong></li>
            @endif
            @if ($variables['newUsersInWaitingListCount'] == 1 )
                <li>🔔 <strong>1 bénévole</strong> veut être averti si vous ouvrez des places sur vos missions</li>
            @elseif($variables['newUsersInWaitingListCount'] > 1)
                <li>🔔 <strong>{{ $variables['newUsersInWaitingListCount'] }} bénévoles</strong> veulent être avertis si vous ouvrez des places sur vos missions</li>
            @endif
        </ul>
    @endcomponent
    @if ($showActions)
        @component('mail::components.paragraph')
            <p>À vous de prendre le relai ! Rendez-vous sur JeVeuxAider.gouv.org pour tenir votre compte à jour :
            </p>
            <ul>
                @if ($variables['participationsWaitingCount'] == 0)
                    <li>🧐 Aucune demande de participation en attente de réponse de votre part</li>
                @elseif($variables['participationsWaitingCount'] == 1)
                    <li>🧐 <strong>{{ $variables['participationsWaitingCount'] }} demande de participation est en attente de réponse</strong> de
                        votre part</li>
                @else
                    <li>🧐 <strong>{{ $variables['participationsWaitingCount'] }} demandes de participation sont en attente de réponse</strong>
                        de votre part</li>
                @endif
                @if ($variables['conversationsUnreadCount'] == 0)
                    <li>📨 Vous n’avez <strong>aucun message non lu</strong></li>
                @elseif($variables['conversationsUnreadCount'] == 1)
                    <li>📨 Vous avez <strong>{{ $variables['conversationsUnreadCount'] }} message non lu</strong></li>
                @else
                    <li>📨 Vous avez <strong>{{ $variables['conversationsUnreadCount'] }} messages non lus</strong></li>
                @endif
                @if ($variables['missionsWithNoPlaceLeftCount'] == 1)
                    <li>💯 Vous avez <strong>1 mission complète</strong>. Augmentez le nombre de bénévoles recherchés, mettez à jour vos participations ou passez la mission en <i>Terminée</i> si vous n’avez plus besoin de bénévoles. À vous de jouez ! + d’infos ici</li>
                @elseif($variables['missionsWithNoPlaceLeftCount'] > 1)
                    <li>💯 Vous avez <strong>{{ $variables['missionsWithRegistrationClosedCount'] }} missions complètes</strong>. Augmentez le nombre de bénévoles recherchés, mettez à jour vos participations ou passez la mission en <i>Terminée</i> si vous n’avez plus besoin de bénévoles. À vous de jouez ! + d’infos ici</li>
                @endif
                @if ($variables['missionsWithRegistrationClosedCount'] == 1)
                    <li>⏸️ Vous avez <strong>1 mission avec les inscriptions fermées</strong>. N’oubliez pas de réouvrir la mission, ou de la mettre en statut <i>Terminée</i> si vous n’avez plus besoin de bénévoles</li>
                @elseif($variables['missionsWithRegistrationClosedCount'] > 1)
                    <li>⏸️ Vous avez <strong>{{ $variables['missionsWithRegistrationClosedCount'] }} missions avec les inscriptions fermées</strong>. N’oubliez pas de réouvrir la mission, ou de la mettre en statut <i>Terminée</i> si vous n’avez plus besoin de bénévoles</li>
                @endif
            </ul>
        @endcomponent
    @endif
    @component('mail::components.paragraph')
        Gardez à l’esprit que les organisations qui répondent vite sont valorisées ; et que les bénévoles privilégient les
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

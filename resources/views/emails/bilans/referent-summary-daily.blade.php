<?php $showActions = true; ?>
@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->first_name }},
    @endcomponent
    @component('mail::components.paragraph')
        <p>Nous sommes heureux de travailler à vos côtés au quotidien ! Voici le récapitulatif des actions sur votre compte
            JeVeuxAider.gouv.fr sur votre département « {{ $department->name }} » sur les 3 derniers jours.</p>
        <ul>
            @if ($variables['newStructuresCount'] == 0)
                <li>🌟 Aucune nouvelle organisation nous a rejoints !</li>
            @elseif($variables['newStructuresCount'] == 1)
                <li>🌟 {{ $variables['newStructuresCount'] }} nouvelle organisation nous a rejoints !</li>
            @else
                <li>🌟 {{ $variables['newStructuresCount'] }} nouvelles organisations nous ont rejoints !</li>
            @endif
            @if ($variables['newMissionsCount'] == 0)
                <li>🔥 Aucune nouvelle mission a été postée</li>
            @elseif($variables['newMissionsCount'] == 1)
                <li>🔥 {{ $variables['newMissionsCount'] }} nouvelle mission a été postée</li>
            @else
                <li>🔥 {{ $variables['newMissionsCount'] }} nouvelles missions ont été postées</li>
            @endif
            @if ($variables['newMessagesCount'] == 0)
                <li>💌 Aucun nouveau message</li>
            @elseif($variables['newMessagesCount'] == 1)
                <li>💌 Vous avez reçu {{ $variables['newMessagesCount'] }} nouveau message</li>
            @else
                <li>💌 Vous avez reçu {{ $variables['newMessagesCount'] }} nouveaux messages</li>
            @endif
            @if ($variables['newNotesCount'] == 0)
                <li>🖊 Aucune nouvelle note postée</li>
            @elseif($variables['newNotesCount'] == 1)
                <li>🖊 {{ $variables['newNotesCount'] }} nouvelle note a été postée</li>
            @else
                <li>🖊 {{ $variables['newNotesCount'] }} nouvelles notes ont été postées</li>
            @endif
        </ul>
    @endcomponent
    @if ($showActions)
        @component('mail::components.paragraph')
            <p>À vous de prendre le relais ! Rendez-vous sur votre compte JeVeuxAider.gouv.org pour :</p>
            <ul>
                @if ($variables['structuresWaitingCount'] == 0)
                    <li>🧐 Aucune organisation en attente</li>
                @elseif($variables['structuresWaitingCount'] == 1)
                    <li>🧐 Modérer l'organisation en attente</li>
                @else
                    <li>🧐 Modérer les {{ $variables['structuresWaitingCount'] }} organisations en attente</li>
                @endif
                @if ($variables['missionsWaitingCount'] == 0)
                    <li>✨ Aucune mission proposée en attente</li>
                @elseif($variables['missionsWaitingCount'] == 1)
                    <li>✨ Prendre connaissance de la mission en attente</li>
                @else
                    <li>✨ Prendre connaissance des {{ $variables['missionsWaitingCount'] }} missions en attente</li>
                @endif
                @if ($variables['conversationsUnreadCount'] == 0)
                    <li>📨 Aucun message non lu</li>
                @elseif($variables['conversationsUnreadCount'] == 1)
                    <li>📨 Lire le message non lu</li>
                @else
                    <li>📨 Lire les {{ $variables['conversationsUnreadCount'] }} messages non lus</li>
                @endif
            </ul>
        @endif
    @endcomponent
    <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
    @component('mail::button', ['url' => $url])
        J'accède à mon compte
    @endcomponent
    <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
    @component('mail::components.paragraph')
        <p>PS : Vous avez des questions ? N’hésitez pas à nous répondre par retour de mail, nous sommes toujours disponibles
            pour vous.</p>
    @endcomponent
@endcomponent

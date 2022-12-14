<?php $showActions = true; ?>

@component('mail::message')
<p>Bonjour {{ $notifiable->first_name}},</p>

<p>Voici le récapitulatif des actions sur vos missions au cours des dernières 24h.</p>

<ul>
@if($variables['newParticipationsCount'] == 0)
<li>🌟 Aucune nouvelle demande de participation</li>
@elseif($variables['newParticipationsCount'] == 1)
<li>🌟 {{ $variables['newParticipationsCount'] }} bénévole a indiqué qu'il souhaitait participer à l'une de vos missions</li>
@else
<li>🌟 {{ $variables['newParticipationsCount'] }} bénévoles ont indiqué qu'ils souhaitaient participer à l'une de vos missions</li>
@endif
@if($variables['newMessagesCount'] == 0)
<li>💌 Aucun nouveau message</li>
@elseif($variables['newMessagesCount'] == 1)
<li>💌 Vous avez reçu {{ $variables['newMessagesCount'] }} nouveau message</li>
@else
<li>💌 Vous avez reçu {{ $variables['newMessagesCount'] }} nouveaux messages</li>
@endif
</ul>

@if($showActions)
<p>À vous de prendre le relais ! Rendez-vous sur votre compte JeVeuxAider.gouv.org pour tenir votre compte à jour :</p>

<ul>
@if($variables['participationsWaitingCount'] == 0)
<li>🧐 Aucune demande de participation en attente de réponse de votre part</li>
@elseif($variables['participationsWaitingCount'] == 1)
<li>🧐 {{ $variables['participationsWaitingCount'] }} demande de participation est en attente de réponse de votre part</li>
@else
<li>🧐 {{ $variables['participationsWaitingCount'] }} demandes de participation sont en attente de réponse de votre part</li>
@endif
@if($variables['conversationsUnreadCount'] == 0)
<li>📨 Aucun message marqué comme "non lu"</li>
@elseif($variables['conversationsUnreadCount'] == 1)
<li>📨 {{ $variables['conversationsUnreadCount'] }} message est marqué comme "non lu"</li>
@else
<li>📨 {{ $variables['conversationsUnreadCount'] }} messages sont marqués comme "non lus"</li>
@endif
</ul>
@endif

<p>Gardez à l’esprit que les associations qui répondent vite sont valorisées ; et que les bénévoles privilégient les missions pour lesquelles ils ont une réponse rapide !</p>

<p>N’oubliez pas : plus vous répondez rapidement aux personnes qui vous contactent, et plus votre mission est valorisée sur la plateforme !</p>

@component('mail::button', ['url' => $url])
Répondre aux bénévoles
@endcomponent

<p>À très vite !<br>
L'équipe de JeVeuxAider.gouv.fr</p>

<p>PS : Vous avez des questions ? N’hésitez pas à nous répondre par retour de mail, nous sommes toujours disponibles pour vous.</p>

@component('mail::footer')
Vous recevez cette notification car vous avez opté pour un résumé quotidien des notifications relatives aux demandes de participations et messages reçus.
Vous pouvez vous rendre dans vos préférences de compte pour passer à des notifications en temps réel.
@endcomponent

@endcomponent


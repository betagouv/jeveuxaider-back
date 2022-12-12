<?php $showActions = true; ?>

@component('mail::message')
Bonjour {{ $notifiable->first_name}},

Voici le récapitulatif des actions sur vos missions au cours des dernières 24h.

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
À vous de prendre le relais ! Rendez-vous sur votre compte JeVeuxAider.gouv.org pour tenir votre compte à jour :

<ul>
@if($variables['participationsWaitingCount'] == 0)
<li>🧐 Aucune demande de participation en attente de validation</li>
@elseif($variables['participationsWaitingCount'] == 1)
<li>🧐 {{ $variables['participationsWaitingCount'] }} bénévole attend votre réponse concernant sa participation</li>
@else
<li>🧐 {{ $variables['participationsWaitingCount'] }} bénévoles attendent votre réponse concernant leur participation</li>
@endif
@if($variables['participationsProcessingCount'] == 0)
<li>⌛ Aucune demande de participation en cours de traitement</li>
@elseif($variables['participationsProcessingCount'] == 1)
<li>⌛ {{ $variables['participationsProcessingCount'] }} bénévole a sa participation en cours de traitement</li>
@else
<li>⌛ {{ $variables['participationsProcessingCount'] }} bénévoles ont leur participation en cours de traitement</li>
@endif
@if($variables['conversationsUnreadCount'] == 0)
<li>📨 Aucun message en attente</li>
@elseif($variables['conversationsUnreadCount'] == 1)
<li>📨 {{ $variables['conversationsUnreadCount'] }} message est encore en attente</li>
@else
<li>📨 {{ $variables['conversationsUnreadCount'] }} messages sont encore en attente</li>
@endif
</ul>
@endif

N’oubliez pas : plus vous répondez rapidement aux personnes qui vous contactent, et plus votre mission est valorisée sur la plateforme !

@component('mail::button', ['url' => $url])
Répondre aux bénévoles
@endcomponent

A très vite !
L'équipe de JeVeuxAider.gouv.fr

PS : Vous avez des questions ? N’hésitez pas à nous répondre par retour de mail, nous sommes toujours disponibles pour vous.

@component('mail::footer')
Vous recevez cette notification car vous avez opté pour un résumé quotidien des notifications relatives aux missions et messages reçus.
Vous pouvez vous rendre dans vos préférences de compte pour passer à des notifications en temps réel.
@endcomponent

@endcomponent


<?php $showActions = true; ?>

@component('mail::message')
Bonjour {{ $notifiable->first_name}},

Voici le récapitulatif des actions sur votre compte JeVeuxAider.gouv.fr au cours des deux derniers jours sur le département « {{ $department->name }} ».

<ul>
@if($variables['newStructuresCount'] == 0)
<li>🌟 Aucune nouvelle organisation nous a rejoints !</li>
@elseif($variables['newStructuresCount'] == 1)
<li>🌟 {{ $variables['newStructuresCount'] }} nouvelle organisation nous a rejoints !</li>
@else
<li>🌟 {{ $variables['newStructuresCount'] }} nouvelles organisations nous ont rejoints !</li>
@endif
@if($variables['newMissionsCount'] == 0)
<li>🔥 Aucune nouvelle mission a été postée</li>
@elseif($variables['newMissionsCount'] == 1)
<li>🔥 {{ $variables['newMissionsCount'] }} nouvelle mission a été postée</li>
@else
<li>🔥 {{ $variables['newMissionsCount'] }} nouvelles missions ont été postées</li>
@endif
@if($variables['newMessagesCount'] == 0)
<li>💌 Aucun nouveau message</li>
@elseif($variables['newMessagesCount'] == 1)
<li>💌 Vous avez reçu {{ $variables['newMessagesCount'] }} nouveau message</li>
@else
<li>💌 Vous avez reçu {{ $variables['newMessagesCount'] }} nouveaux messages</li>
@endif
@if($variables['newNotesCount'] == 0)
<li>🖊 Aucune nouvelle note postée</li>
@elseif($variables['newNotesCount'] == 1)
<li>🖊 {{ $variables['newNotesCount'] }} nouvelle note a été postée</li>
@else
<li>🖊 {{ $variables['newNotesCount'] }} nouvelles notes ont été postées</li>
@endif
</ul>

@if($showActions)
À vous de prendre le relais ! Rendez-vous sur votre compte JeVeuxAider.gouv.org pour : 

<ul>
@if($variables['structuresWaitingCount'] == 0)
<li>🧐 Aucune organisation en attente de validation</li>
@elseif($variables['structuresWaitingCount'] == 1)
<li>🧐 Modérer l'organisation en attente de validation</li>
@else
<li>🧐 Modérer les {{ $variables['structuresWaitingCount'] }} organisations en attente de validation</li>
@endif
@if($variables['missionsWaitingCount'] == 0)
<li>✨ Aucune mission proposée en attente de validation</li>
@elseif($variables['missionsWaitingCount'] == 1)
<li>✨ Prendre connaissance de la mission en attente de validation</li>
@else
<li>✨ Prendre connaissance des {{ $variables['missionsWaitingCount'] }} missions en attente de validation</li>
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

@component('mail::button', ['url' => $url])
Accéder à mon compte
@endcomponent

A très vite !
L'équipe de JeVeuxAider.gouv.fr

PS : Vous avez des questions ? N’hésitez pas à nous répondre par retour de mail, nous sommes toujours disponibles pour vous.

@component('mail::footer')
Vous recevez cette notification car vous avez opté pour un résumé quotidien des notifications relatives aux missions/organisations et messages reçus.
Vous pouvez vous rendre dans vos préférences de compte pour passer à des notifications en temps réel.
@endcomponent

@endcomponent


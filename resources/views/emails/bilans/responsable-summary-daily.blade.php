<?php $showWaitingActions = $variables['participationsWaitingCount'] > 0 || $variables['participationsProcessingCount'] > 0; ?>

@component('mail::message')
Bonjour {{ $notifiable->first_name}},

Voici le bilan de la journée passée

<ul>
<li>{{ $variables['newParticipationsCount'] }} nouvelles candidatures</li>
<li>{{ $variables['newMessagesCount'] }} nouveaux messages</li>
</ul>

@if($showWaitingActions)
Et pour rappel, voici vos actions en attente

<ul>
<li>{{ $variables['participationsWaitingCount'] }} participations en attente de validation</li>
<li>{{ $variables['participationsProcessingCount'] }} participations en cours de modération</li>
</ul>
@endif

@component('mail::button', ['url' => $url])
Accéder à vote compte
@endcomponent

Belle journée,

L'équipe de JeVeuxAider.gouv.fr

@component('mail::footer')
Vous recevez cette notification car vous avez opté pour un résumé quotidien des notifications relatives aux missions et messages reçus.
Vous pouvez vous rendre dans vos préférences de compte pour passer à des notifications en temps réel.
@endcomponent

@endcomponent


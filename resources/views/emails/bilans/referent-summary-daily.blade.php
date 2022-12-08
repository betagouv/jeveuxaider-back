<?php $showWaitingActions = $variables['missionsWaitingCount'] > 0 || $variables['missionsProcessingCount'] > 0 || $variables['organisationsWaitingCount'] > 0 || $variables['organisationsProcessingCount'] > 0; ?>

@component('mail::message')
Bonjour {{ $notifiable->first_name}},

Voici le bilan de la journée passée sur votre département « {{ $department->name }} »

<ul>
<li>{{ $variables['newMessagesCount'] }} nouveaux messages</li>
<li>{{ $variables['newMissionsCount'] }} nouvelles missions postées</li>
<li>{{ $variables['newOrganisationsCount'] }} nouvelles organisations inscrites</li>
</ul>

@if($showWaitingActions)
Et pour rappel, voici vos actions en attente

<ul>
<li>{{ $variables['missionsWaitingCount'] }} participations en attente de validation</li>
<li>{{ $variables['missionsProcessingCount'] }} participations en cours de modération</li>
<li>{{ $variables['organisationsProcessingCount'] }} organisations en attente de validation</li>
<li>{{ $variables['organisationsProcessingCount'] }} organisations en cours de modération</li>
</ul>
@endif

@component('mail::button', ['url' => $url])
Accéder à vote compte
@endcomponent

Belle journée,

L'équipe de JeVeuxAider.gouv.fr

@component('mail::footer')
Vous recevez cette notification car vous avez opté pour un résumé quotidien des notifications relatives aux participations et messages reçus.
Vous pouvez vous rendre dans vos préférences de compte pour passer à des notifications en temps réel.
@endcomponent

@endcomponent


<?php $showSummaryActions = true; ?>

@component('mail::message')
Bonjour {{ $notifiable->first_name}},

Voici le récapitulatif de vos missions au cours du dernier mois.

<ul>
@if($variables['newMissionsCount'] == 0)
<li>🥳 Vous n'avez pas mis en ligne de nouvelle mission sur la plateforme</li>
@elseif($variables['newMissionsCount'] == 1)
<li>🥳 Vous avez mis en ligne {{ $variables['newMissionsCount'] }} mission sur la plateforme</li>
@else
<li>🥳 Vous avez mis en ligne {{ $variables['newMissionsCount'] }} missions sur la plateforme</li>
@endif
@if($variables['missionsOnlineCount'] == 0)
<li>🤩 Vous n'avez plus de mission active sur la plateforme</li>
@elseif($variables['missionsOnlineCount'] == 1)
<li>🤩 Vous avez désormais {{ $variables['missionsOnlineCount'] }} mission active sur la plateforme</li>
@else
<li>🤩 Vous avez désormais {{ $variables['missionsOnlineCount'] }} missions actives sur la plateforme</li>
@endif
</ul>

@if($showSummaryActions)
JeVeuxAider.gouv.org vous accompagne dans la recherche de bénévoles ! Vous avez reçu : 

<ul>
@if($variables['newParticipationsCount'] == 0)
<li>🔥 Aucune demande de participation de la part de bénévoles sur vos missions</li>
@elseif($variables['newParticipationsCount'] == 1)
<li>🔥 {{ $variables['newParticipationsCount'] }} demande de participation de la part de bénévoles sur vos missions</li>
@else
<li>🔥 {{ $variables['newParticipationsCount'] }} demandes de participation de la part de bénévoles sur vos missions</li>
@endif
</ul>
@endif

@component('mail::button', ['url' => $url])
Accéder à mon compte
@endcomponent

À très vite pour de nouvelles missions !
L'équipe de JeVeuxAider.gouv.fr

@component('mail::footer')
Vous recevez cette notification car vous avez opté pour un résumé mensuel de votre activité.
Vous pouvez vous rendre dans vos préférences de compte pour désactiver cette notification.
@endcomponent

@endcomponent


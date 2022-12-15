<?php $showSummaryActions = $structure->response_time || $structure->response_ratio; ?>

@component('mail::message')
<p>Bonjour {{ $notifiable->first_name}},</p>

<p>Nous sommes ravis de vous compter parmi nous sur JVA ! Voici l’activité reçue sur JVA au cours du dernier mois. </p>

<ul>
@if($variables['newMissionsCount'] == 0)
<li>🥳 Vous n'avez pas mis en ligne de nouvelle mission sur la plateforme</li>
@elseif($variables['newMissionsCount'] == 1)
<li>🥳 Vous avez mis en ligne {{ $variables['newMissionsCount'] }} mission sur la plateforme</li>
@else
<li>🥳 Vous avez mis en ligne {{ $variables['newMissionsCount'] }} missions sur la plateforme</li>
@endif
@if($variables['missionsOnlineCount'] == 0)
<li>🤩 Vous n'avez pas de mission active sur la plateforme</li>
@elseif($variables['missionsOnlineCount'] == 1)
<li>🤩 Vous avez désormais {{ $variables['missionsOnlineCount'] }} mission active sur la plateforme</li>
@else
<li>🤩 Vous avez désormais {{ $variables['missionsOnlineCount'] }} missions actives sur la plateforme</li>
@endif
@if($variables['newParticipationsCount'] == 0)
<li>🔥 Aucune demande de participation de la part de bénévoles sur vos missions</li>
@elseif($variables['newParticipationsCount'] == 1)
<li>🔥 {{ $variables['newParticipationsCount'] }} demande de participation de la part de bénévoles sur vos missions</li>
@else
<li>🔥 {{ $variables['newParticipationsCount'] }} demandes de participation de la part de bénévoles sur vos missions</li>
@endif
</ul>

@if($showSummaryActions)
<p>JeVeuxAider.gouv.org vous permet de toujours prendre mieux soin de vos bénévoles !</p>
<ul>
<li>⌚ En moyenne, vous avez répondu à vos bénévoles en {{ round($structure->response_time / 60 / 60) }} heure(s).</li>
<li>👍 Vous avez répondu à {{ $structure->response_ratio }}% de vos demandes.</li>
</ul>

<p>Gardez à l’esprit que les associations qui répondent vite sont valorisées ; et que les bénévoles privilégient les missions pour lesquelles ils ont une réponse rapide !</p>
@endif

@component('mail::button', ['url' => $url])
J'accède à mon compte
@endcomponent

<p>À très vite pour de nouvelles missions !<br>
L'équipe de JeVeuxAider.gouv.fr</p>

@component('mail::footer')
Vous recevez cette notification car vous avez opté pour un résumé mensuel de votre activité.
Vous pouvez vous rendre dans vos préférences de compte pour désactiver cette notification.
@endcomponent

@endcomponent


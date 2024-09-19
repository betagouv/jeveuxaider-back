<?php $showSummaryActions = $structure->score->response_time || $structure->score->processed_participations_rate; ?>
@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->first_name }},
    @endcomponent
    @component('mail::components.paragraph')
        <p>Nous sommes ravis de vous compter parmi nous sur JeVeuxAider.gouv.fr ! Voici l’activité reçue pour
            « {{ $structure->name }} » au cours du dernier mois. </p>
        <ul>
            @if ($variables['newMissionsCount'] == 0)
                <li>🥳 Vous n'avez pas mis en ligne de nouvelle mission sur la plateforme</li>
            @elseif($variables['newMissionsCount'] == 1)
                <li>🥳 Vous avez mis en ligne {{ $variables['newMissionsCount'] }} mission sur la plateforme</li>
            @else
                <li>🥳 Vous avez mis en ligne {{ $variables['newMissionsCount'] }} missions sur la plateforme</li>
            @endif
            @if ($variables['missionsOnlineCount'] == 0)
                <li>🤩 Vous n'avez pas de mission active sur la plateforme</li>
            @elseif($variables['missionsOnlineCount'] == 1)
                <li>🤩 Vous avez désormais {{ $variables['missionsOnlineCount'] }} mission active sur la plateforme</li>
            @else
                <li>🤩 Vous avez désormais {{ $variables['missionsOnlineCount'] }} missions actives sur la plateforme</li>
            @endif
            @if ($variables['newParticipationsCount'] == 0)
                <li>🔥 Aucune demande de participation de la part de bénévoles sur vos missions</li>
            @elseif($variables['newParticipationsCount'] == 1)
                <li>🔥 {{ $variables['newParticipationsCount'] }} demande de participation de la part de bénévoles sur vos
                    missions</li>
            @else
                <li>🔥 {{ $variables['newParticipationsCount'] }} demandes de participation de la part de bénévoles sur vos
                    missions</li>
            @endif
        </ul>
    @endcomponent
    @if ($showSummaryActions)
        @component('mail::components.paragraph')
            <p>JeVeuxAider.gouv.org vous permet de toujours prendre mieux soin de vos bénévoles !</p>
            <ul>
                <li>⌚ En moyenne, vous avez répondu à vos bénévoles en {{ round($structure->score->response_time / 60 / 60) }}
                    heure(s).</li>
                <li>👍 Vous avez répondu à {{ $structure->score->processed_participations_rate }}% de vos demandes.</li>
            </ul>
            <p>Gardez à l’esprit que les organisations qui répondent vite sont valorisées ; et que les bénévoles privilégient les
                missions pour lesquelles ils ont une réponse rapide !</p>
        @endcomponent
    @endif
    @component('mail::components.space', ['height' => 33])@endcomponent
    @component('mail::button', ['url' => $url])
        J'accède à mon compte
    @endcomponent
    @component('mail::components.space', ['height' => 33])@endcomponent
@endcomponent

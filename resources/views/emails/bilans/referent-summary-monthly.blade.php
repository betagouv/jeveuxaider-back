<?php $showSummaryActions = true; ?>
@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->first_name }},
    @endcomponent
    @component('mail::components.paragraph')
        <p>C’est l’heure du bilan ! Voici le récapitulatif des actions sur votre département « {{ $department->name }} » au
            cours du dernier mois.
        <ul>
            @if ($variables['newStructuresCount'] == 0)
                <li>🎉 Aucune nouvelle organisation inscrite</li>
            @elseif($variables['newStructuresCount'] == 1)
                <li>🎉 {{ $variables['newStructuresCount'] }} nouvelle organisation nous a rejoints !</li>
            @else
                <li>🎉 {{ $variables['newStructuresCount'] }} nouvelles organisations nous ont rejoints !</li>
            @endif
            @if ($variables['newMissionsCount'] == 0)
                <li>🥂 Aucune nouvelle mission postée</li>
            @elseif($variables['newMissionsCount'] == 1)
                <li>🥂 {{ $variables['newMissionsCount'] }} nouvelle mission a été postée</li>
            @else
                <li>🥂 {{ $variables['newMissionsCount'] }} nouvelles missions ont été postées</li>
            @endif
            @if ($variables['newParticipationsCount'] == 0)
                <li>🌟 Aucune nouvelle demande de participation</li>
            @elseif($variables['newParticipationsCount'] == 1)
                <li>🌟 {{ $variables['newParticipationsCount'] }} nouvelle demande de participation</li>
            @else
                <li>🌟 {{ $variables['newParticipationsCount'] }} nouvelles demandes de participation</li>
            @endif
            @if ($variables['newBenevolesCount'] == 0)
                <li>🎯 Aucun bénévole ne s'est inscrit</li>
            @elseif($variables['newBenevolesCount'] == 1)
                <li>🎯 {{ $variables['newBenevolesCount'] }} bénévole s'est inscrit sur la plateforme</li>
            @else
                <li>🎯 {{ $variables['newBenevolesCount'] }} bénévoles se sont inscrits sur la plateforme </li>
            @endif
        </ul>
    @endcomponent
    @if ($showSummaryActions)
        @component('mail::components.paragraph')
            <p>À l'heure d'aujourd'hui, voici l'offre de JeVeuxAider.gouv.fr sur le département « {{ $department->name }} » :
            </p>
            <ul>
                @if ($variables['structuresActivesCount'] == 0)
                    <li>🌟 Aucune organisation n'est active</li>
                @elseif($variables['structuresActivesCount'] == 1)
                    <li>🌟 {{ $variables['structuresActivesCount'] }} organisation est active</li>
                @else
                    <li>🌟 {{ $variables['structuresActivesCount'] }} organisations sont actives</li>
                @endif
                @if ($variables['missionsOnlineCount'] == 0)
                    <li>🔥 Aucune mission n'est disponible sur la plateforme</li>
                @elseif($variables['missionsOnlineCount'] == 1)
                    <li>🔥 {{ $variables['missionsOnlineCount'] }} mission est disponible sur la plateforme</li>
                @else
                    <li>🔥 {{ $variables['missionsOnlineCount'] }} missions sont disponibles sur la plateforme</li>
                @endif
                @if ($variables['placesLeftCount'] == 0)
                    <li>✅ Aucune place n'est disponible sur les missions</li>
                @elseif($variables['placesLeftCount'] == 1)
                    <li>✅ Il reste {{ $variables['placesLeftCount'] }} place disponible sur les missions</li>
                @else
                    <li>✅ Il reste {{ $variables['placesLeftCount'] }} places disponibles sur les missions</li>
                @endif
            </ul>
        @endcomponent
    @endif
    <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
    @component('mail::button', ['url' => $url])
        J'accède aux statistiques
    @endcomponent
    <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
@endcomponent

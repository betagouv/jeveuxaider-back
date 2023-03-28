<?php $showActions = true; ?>
@component('mail::message')
    @component('mail::components.headline')
        Il y a du nouveau dans votre département ! 🤩
    @endcomponent
    @component('mail::components.paragraph')
        <p>Votre action est requise pour permettre la publication de nouveaux contenus :</p>
        <ul>
            @if ($variables['newStructuresCount'] == 0)
                <li>🌟 Aucune nouvelle organisation en attente de validation</li>
            @elseif($variables['newStructuresCount'] == 1)
                <li>🌟 {{ $variables['newStructuresCount'] }} nouvelle organisation en attente de validation</li>
            @else
                <li>🌟 {{ $variables['newStructuresCount'] }} nouvelles organisations en attente de validation</li>
            @endif
            @if ($variables['newMissionsCount'] == 0)
                <li>🔥 Aucune nouvelle mission en attente de validation</li>
            @elseif($variables['newMissionsCount'] == 1)
                <li>🔥 {{ $variables['newMissionsCount'] }} nouvelle mission en attente de validation</li>
            @else
                <li>🔥 {{ $variables['newMissionsCount'] }} nouvelles missions en attente de validation</li>
            @endif
        </ul>
    @endcomponent
    @component('mail::button', ['url' => $url])
        Gérer les contenus
    @endcomponent
    @component('mail::components.space', ['height' => 33])
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        N’hésitez pas à nous répondre par retour de mail, nous sommes toujours disponibles
        pour vous.
    @endcomponent
@endcomponent

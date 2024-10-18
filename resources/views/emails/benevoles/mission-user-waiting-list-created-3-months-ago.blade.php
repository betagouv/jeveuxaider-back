@component('mail::message')
    @component('mail::components.headline')
        En attendant qu’une place se libère, nous avons de nouvelles missions pour vous !
    @endcomponent
    @component('mail::components.paragraph')
        <p>Il y a quelques semaines, vous avez demandé à être averti si vous pouviez à nouveau proposer votre aide sur cette mission :</p>
        <p><strong>{{ $mission->name }}</strong></p>
        <p style="font-size: 12px;">{{ $missionUrl }}<p>
        <p>Malheureusement, les inscriptions sont encore fermées. Mais de nombreuses autres associations cherchent des bénévoles !</p>
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Jetez un coup d’oeil à ces missions, elles pourraient aussi vous plaire'])
        @foreach ($proposedMissions as $proposedMission)
            @component('mail::components.card-mission-teaser', ['mission' => $proposedMission, 'url' => $proposedMission->full_base_url])
            @endcomponent
            @component('mail::components.divider', ['spaceTop' => 16, 'spaceBottom' => 16])
            @endcomponent
        @endforeach
    @endcomponent
    @component('mail::button', ['url' => $url])
        Plus de missions similaires
    @endcomponent
    @component('mail::components.divider', ['spaceTop' => 48, 'spaceBottom' => 32])
    @endcomponent
    @component('mail::components.paragraph')
        Si vous n’êtes plus en recherche, vous pouvez supprimer votre alerte depuis la <a href="{{ $missionUrl }}">page de la mission</a>.
    @endcomponent
    @component('mail::components.divider', ['spaceTop' => 16, 'spaceBottom' => 16])
    @endcomponent
    @component('mail::components.tips')
        <p><strong>Vous ne savez plus quelle mission chercher ?</strong></p>
        <p>Répondez à notre quiz en 2 minutes top chrono pour trouver la mission qui vous convient.</p>
        <p><a class="link" href="{{ $quizUrl }}">Répondre à notre quiz</a></p>
    @endcomponent
@endcomponent
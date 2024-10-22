@component('mail::message')
    @component('mail::components.headline')
        En attendant qu’une place se libère, nous avons de nouvelles missions pour vous !
    @endcomponent
    @component('mail::components.paragraph')
        <p>Il y a quelques semaines, vous avez demandé à être averti si vous pouviez à nouveau proposer votre aide sur cette mission :</p>
        <p><strong>{{ $mission->name }}</strong></p>
        <p>Malheureusement, les inscriptions sont encore fermées. Mais de nombreuses autres associations cherchent des bénévoles !</p>
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Jetez un coup d’oeil à ces missions, elles pourraient aussi vous plaire'])
        @foreach ($proposedMissions as $proposedMission)
            @component('mail::components.divider', ['spaceTop' => 16, 'spaceBottom' => 16])
            @endcomponent
            @component('mail::components.card-mission-teaser', ['mission' => $proposedMission, 'url' => $proposedMission->full_base_url])
            @endcomponent
        @endforeach
    @endcomponent
    @component('mail::components.divider', ['spaceTop' => 0, 'spaceBottom' => 0])
    @endcomponent
    @component('mail::components.tips')
        <p><strong style="color: #161616;font-size: 22px;">Pas tout à fait convaincu ?</strong></p>
        <p>Répondez à notre quiz en 2 minutes top chrono pour trouver la mission qui vous convient.</p>
        @component('mail::button', ['url' => $quizUrl, 'align' => 'left'])
            Trouver une mission
        @endcomponent
    @endcomponent
    @component('mail::components.space', ['height' => 24])
    @endcomponent
    @component('mail::components.paragraph')
        Si vous n’êtes plus en recherche, vous pouvez supprimer votre alerte depuis la <a href="{{ $missionUrl }}">page de la mission</a>.
    @endcomponent
@endcomponent
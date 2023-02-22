@component('mail::message')
    @component('mail::components.headline')
        Votre mission est validée 👍
    @endcomponent
    @component('mail::components.paragraph')
        Elle est désormais publiée et accessible à tous ! Dès qu'un bénévole candidatera, nous vous transmettrons ses
        coordonnées pour que vous puissiez échanger et valider ou non sa participation.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'N’oubliez pas !'])
        Soyez réactif pour garder vos bénévoles motivés !
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        {{ $mission->name }}
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <a class="link" href="{{ $missionUrl }}">Voir la mission en ligne ›</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Passez au niveau supérieur'])
        Vous avez la possibilité de contacter directement des bénévoles pour leur proposer votre mission.
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        @component('mail::button', ['url' => $marketPlaceUrl, 'align' => 'left'])
            Contacter des bénévoles
        @endcomponent
    @endcomponent
@endcomponent

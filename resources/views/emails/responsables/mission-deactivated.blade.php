@component('mail::message')
    @component('mail::components.headline')
        Votre mission a été suspendue 😯
    @endcomponent
    @component('mail::components.paragraph')
        Ce n’est pas une punition, c’est juste que trop de bénévoles attendent un retour. La mission a de ce fait été désactivée par un modérateur, le temps que vous puissiez mettre le statut des participations à jour. Elle a été enlevée de la recherche et il n'est plus possible pour de nouveaux bénévoles de s'y inscrire.
    @endcomponent
    @component('mail::components.paragraph', ['title' => "Besoin d'aide ?"])
        Une personne du support va rentrer en contact avec vous afin de vous accompagner.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        {{ $mission->name }}
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <a class="link" href="{{ $missionUrl }}">Voir la mission ›</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'N’oubliez pas !'])
        Il est important de rester réactif pour garder vos bénévoles motivés ! Suivez ce lien pour mettre à jour les participations de la mission
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        @component('mail::button', ['url' => $dashboardParticipationsUrl, 'align' => 'left'])
            Gérer les participations
        @endcomponent
    @endcomponent
@endcomponent

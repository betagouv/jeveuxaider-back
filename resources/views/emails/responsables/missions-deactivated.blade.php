@component('mail::message')
    @component('mail::components.headline')
        Vos missions ont été suspendues 😯
    @endcomponent
    @component('mail::components.paragraph')
        Ce n’est pas une punition, c’est juste que trop de bénévoles attendent un retour. Vos missions ont de ce fait été désactivée par un modérateur, le temps que vous puissiez mettre le statut des participations à jour. Elles ne sont plus visibles dans la recherche et il n'est plus possible pour de nouveaux bénévoles de s'y inscrire.
    @endcomponent
    @component('mail::components.paragraph', ['title' => "Besoin d'aide ?"])
        Une personne du support va rentrer en contact avec vous afin de vous accompagner.
    @endcomponent
    @component('mail::components.tips', ['title' => 'N’oubliez pas !'])
        Il est important de rester réactif pour garder vos bénévoles motivés ! Suivez ce lien pour mettre à jour les participations
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        @component('mail::button', ['url' => $dashboardParticipationsUrl, 'align' => 'left'])
            Traiter les participations
        @endcomponent
    @endcomponent
@endcomponent

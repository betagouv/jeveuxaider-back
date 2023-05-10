@component('mail::message')
    @component('mail::components.headline')
        Votre mission a été désactivée 😢
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'La mission'])
        <p>
            {{ $mission->name }}<br>
            <a class="link" href="{{ $missionUrl }}">Voir la mission ›</a>
        </p>
    @endcomponent
    @component('mail::components.paragraph')
        <p>Lorsque vous publiez une mission sur <a href="https://www.jeveuxaider.gouv.fr/">JeVeuxAider.gouv.fr</a>, vous vous engagez à <strong>mettre à jour le statut des participations (Validée ou Refusée) sous 2 mois</strong>.</p>
        <p>Actuellement, plusieurs participations ne sont pas modérées.</p>
        <p>Pour éviter d’avoir de nouvelles participations à mettre à jour, la mission a été ponctuellement désactivée. Elle n’est plus visible depuis la recherche, et les bénévoles ne peuvent plus proposer leur aide.</p>
        <p>Nous avons à coeur de vous accompagner dans la régularisation de cette situation et la compréhension de vos besoins, une personne du support prendra contact avec vous 🙂</p>
    @endcomponent
    @component('mail::button', ['url' => $dashboardParticipationsUrl])
        Traiter les participations
    @endcomponent
    @component('mail::components.space', ['height' => 24])
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent
@endcomponent

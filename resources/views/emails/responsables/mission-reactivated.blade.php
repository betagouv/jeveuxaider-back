@component('mail::message')
    @component('mail::components.headline')
        Bonne nouvelle ! 🥳
    @endcomponent
    @component('mail::components.paragraph')
        <p>Votre mission est de nouveau en ligne. Elle est visible depuis la recherche, et des bénévoles peuvent proposer leur aide. Au nom de <a class="link" href="https://www.jeveuxaider.gouv.fr/">JeVeuxAider.gouv.fr</a> et des bénévoles, merci, et bravo d’avoir régularisé la situation 💪🏻</p>
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Rappel de la mission'])
        {{ $mission->name }}<br>
        <a class="link" href="{{ $missionUrl }}">Voir la mission ›</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent
@endcomponent

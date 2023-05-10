@component('mail::message')
    @component('mail::components.headline')
        Bonne nouvelle ! 🥳
    @endcomponent
    @component('mail::components.paragraph')
        Vos missions sont de nouveau actives. Elles sont visibles depuis la recherche, et des bénévoles peuvent proposer leur aide. Au nom de <a class="link" href="https://www.jeveuxaider.gouv.fr/">JeVeuxAider.gouv.fr</a> et des bénévoles, merci, et bravo d’avoir régularisé la situation 💪🏻
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent
@endcomponent

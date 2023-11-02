@component('mail::message')
    @component('mail::components.headline')
        Bonjour,
    @endcomponent
    @component('mail::components.paragraph')
        Vous avez été désinscrit de la plateforme JeVeuxAider.gouv.fr car vous ne répondez pas aux conditions d’éligibilité.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Motif de la désinscription :'])
        Le bénévole a un comportement inadapté (insulte, propos discriminatoires / racistes, …)
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent

    @slot('signature')
        @component('mail::signature', ['regards' => 'Bien à vous,'])
        @endcomponent
    @endslot
@endcomponent

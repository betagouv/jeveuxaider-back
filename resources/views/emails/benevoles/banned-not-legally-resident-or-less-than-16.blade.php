@component('mail::message')
    @component('mail::components.headline')
        Bonjour,
    @endcomponent
    @component('mail::components.paragraph')
        Vous avez été désinscrit de la plateforme JeVeuxAider.gouv.fr car vous ne répondez pas aux conditions d’éligibilité.
    @endcomponent
    @component('mail::components.paragraph')
        En effet, pour proposer votre aide à une mission de bénévolat, vous devez résider régulièrement sur le territoire français et être âgé de 16 ans ou plus.
    @endcomponent
@endcomponent

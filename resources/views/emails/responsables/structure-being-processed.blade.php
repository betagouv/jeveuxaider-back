@component('mail::message')
    @component('mail::components.headline')
        L'inscription de votre organisation est en cours de traitement ⏳
    @endcomponent
    @component('mail::components.paragraph')
        L’inscription de <strong>{{ $structure->name }}</strong> sur notre plateforme est en attente. Un référent départemental
        de
        JeVeuxAider.gouv.fr
        doit vous contacter dans les prochains jours pour valider certains points avec vous, via la messagerie ou directement
        grâce à vos coordonnées renseignées.
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        Contactez le support en répondant à cet e-mail, nous sommes à votre disposition pour vous guider sur la plateforme.
    @endcomponent
@endcomponent

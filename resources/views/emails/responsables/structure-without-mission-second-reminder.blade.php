@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->profile->first_name }} 👋
    @endcomponent
    @component('mail::components.paragraph')
        Saviez-vous que <a class="link" href="https://www.jeveuxaider.gouv.fr/">JeVeuxAider.gouv.fr</a> représente aujourd'hui 460 000 bénévoles inscrits. Lors de notre dernière étude d’impact, 78% des bénévoles interrogés nous confiaient que <a class="link" href="https://www.jeveuxaider.gouv.fr/">JeVeuxAider.gouv.fr</a> facilite le passage à l’action et 88% se disaient satisfaits ou très satisfaits de la plateforme.
    @endcomponent
    @component('mail::components.paragraph')
        Et si vous mettiez au service de votre projet associatif cette capacité à mobiliser ? Publiez pour la première fois vos besoins de bénévoles sur la plateforme publique du bénévolat.
    @endcomponent
    @component('mail::button', ['url' => $url])
        Proposer une mission
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        Contactez le support en répondant à cet e-mail, nous sommes à votre disposition pour vous guider sur la plateforme.
    @endcomponent
@endcomponent

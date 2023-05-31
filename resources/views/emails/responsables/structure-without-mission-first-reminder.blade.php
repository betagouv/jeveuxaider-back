@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->profile->first_name }} 👋
    @endcomponent
    @component('mail::components.paragraph')
        Nous avons remarqué que vous n’avez pas publié de mission sur <a class="link" href="https://www.jeveuxaider.gouv.fr/">JeVeuxAider.gouv.fr</a>. Si vous avez besoin de bénévoles, cette étape ne vous prendra que 5 minutes et peut s’avérer très efficace !
    @endcomponent
    @component('mail::button', ['url' => $url])
        Proposer une mission
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        Contactez le support en répondant à cet e-mail, nous sommes à votre disposition pour vous guider sur la plateforme.
    @endcomponent
@endcomponent

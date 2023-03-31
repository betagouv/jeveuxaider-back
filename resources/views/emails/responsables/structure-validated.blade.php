@component('mail::message')
    @component('mail::components.headline')
        Bonne nouvelle, votre organisation est validée 👍
    @endcomponent
    @component('mail::components.paragraph')
        Votre organisation <strong>{{ $structure->name }}</strong> vient d’être validée. Vos missions sont désormais visibles
        par les bénévoles.
    @endcomponent
    @component('mail::components.paragraph')
        Pour publier une nouvelle mission, rendez-vous directement sur votre espace organisation.
    @endcomponent
    @component('mail::button', ['url' => $url])
        Publier une mission
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        Contactez le support en répondant à cet e-mail, nous sommes à votre disposition pour vous guider sur la plateforme.
    @endcomponent
@endcomponent

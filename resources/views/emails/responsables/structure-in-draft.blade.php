@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->profile->first_name }} 👋
    @endcomponent
    @component('mail::components.paragraph')
        Vous êtes à quelques clics de pouvoir recruter de nouveaux bénévoles !
    @endcomponent
    @component('mail::components.paragraph')
        Nous ne pouvons pas publier votre mission à l’heure actuelle car nous manquons d’informations sur votre structure.
    @endcomponent
    @component('mail::components.space', ['height' => 16])
    @endcomponent
    @component('mail::button', ['url' => $url, 'subtitle' => '⏱️ 2 minutes'])
        Complétez vos informations
    @endcomponent
    @component('mail::components.tips', ['title' => 'Rejoignez le mouvement'])
        <strong>73 % des associations</strong> déclarent recruter <strong>plus facilement</strong> des bénévoles grâce à
        JeVeuxAider.gouv.fr.
    @endcomponent
@endcomponent

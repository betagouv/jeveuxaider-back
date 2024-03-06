@component('mail::message')
    @component('mail::components.headline')
        Bonjour,
    @endcomponent
    @component('mail::components.paragraph')
        Nous avons reçu votre demande de code à usage unique à utiliser avec votre compte JeVeuxAider.gouv.fr.
    @endcomponent
    @component('mail::components.code', ['code' => $code])
    @endcomponent
    @component('mail::components.tips', ['title' => 'Ce n\'est pas vous ?'])
        Si vous n’avez demandé aucun code, vous pouvez ignorer cet e-mail. Un autre utilisateur a peut-être indiqué votre
        adresse e-mail par erreur.
    @endcomponent
@endcomponent

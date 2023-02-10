@component('mail::message')
    @component('mail::components.headline')
        Merci d'avoir proposé votre mission 🙏
    @endcomponent
    @component('mail::components.paragraph')
        Nous devons la valider pour qu’elle soit publiée sur la plateforme. Nous allons vérifier qu’elle correspond bien au
        cadre défini par notre <a class="link" href="#">Charte de la Réserve Civique ›</a>
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Bon à savoir :'])
        Votre mission sera examinée par le référent JeVeuxAider.gouv.fr de votre département et cela peut prendre jusqu’à 7
        jours.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission :'])
        {{ $mission->name }}
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <a class="link" href="{{ $url }}">Plus de détails ›</a>
    @endcomponent
@endcomponent

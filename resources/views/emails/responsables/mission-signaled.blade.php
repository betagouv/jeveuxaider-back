@component('mail::message')
    @component('mail::components.headline')
        Votre mission ne pourra pas être publiée ✋
    @endcomponent
    @component('mail::components.paragraph')
        Cette mission ne répond malheureusement pas à nos critères de publication définis dans le cadre de notre <a
            class="link" href="https://www.jeveuxaider.gouv.fr/charte-reserve-civique">Charte de la Réserve Civique ›</a>
    @endcomponent
    @component('mail::components.paragraph')
        Par conséquent, les bénévoles ne peuvent pas s’y inscrire. De plus, si certains s’étaient déjà inscrits, ils ont été
        informés que la mission est désormais annulée.
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission :'])
        {{ $mission->name }}
        @component('mail::components.space', ['height' => 10])
        @endcomponent
        <a class="link" href="{{ $url }}">Plus de détails ›</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        Contactez le support en répondant à cet e-mail, nous sommes à votre disposition pour vous guider sur la plateforme.
    @endcomponent
@endcomponent

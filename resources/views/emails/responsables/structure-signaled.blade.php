@component('mail::message')
    @component('mail::components.headline')
        Votre organisation n’est plus sur la plateforme ✋
    @endcomponent
    @component('mail::components.paragraph')
        <strong>{{ $structure->name }}</strong> a dû être dépubliée de notre plateforme car elle ne répond malheureusement pas à
        nos critères définis dans le cadre de notre <a class="link"
            href="{{ $urlCharte }}">Charte de la Réserve
            Civique ›</a>
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Qu’est-ce que cela signifie ?'])
        <ul>
            <li>Vous ne pouvez pas publier de nouvelles missions via JeVeuxAider.gouv.fr</li>
            <li>Vous ne pouvez pas engager de nouveaux bénévoles</li>
            <li>Les missions déjà publiées ne sont désormais plus visibles sur la plateforme</li>
            <li>Les bénévoles qui étaient inscrits à l’une de vos missions ont été informés de son annulation</li>
        </ul>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        Contactez le support en répondant à cet e-mail, nous sommes à votre disposition pour vous guider sur la plateforme.
    @endcomponent
@endcomponent

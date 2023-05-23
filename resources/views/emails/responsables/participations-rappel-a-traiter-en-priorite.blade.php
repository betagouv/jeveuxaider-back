@component('mail::message')
    @component('mail::components.headline')
        @if ($totalCount > 1)
            {{ $totalCount }} bénévoles souhaitent vous aider 🙌
        @else
            1 bénévole souhaite vous aider 🙌
        @endif
    @endcomponent
    @component('mail::components.paragraph')
        <p>Vous avez actuellement :</p>
        <ul>
            <li>{{ $waitingCount }} participation{{ $waitingCount > 1 ? 's' : '' }} en attente depuis plus de 10 jours</li>
            <li>{{ $inProgressCount }} participation{{ $inProgressCount > 1 ? 's' : '' }} en cours depuis plus de 2 mois</li>
        </ul>
    @endcomponent
    @if ($totalCount > 20)
        @component('mail::components.paragraph')
            Ces participations doivent être validées ou refusées au plus vite. En effet, la plateforme JeVeuxAider.gouv.fr exige un traitement (validées ou refusées) des participations, sans quoi vous risquez de voir vos missions dépubliées.
        @endcomponent
    @endif
    @component('mail::button', ['url' => $url])
        Traiter les participations
    @endcomponent
    @component('mail::components.tips', ['title' => 'Besoin d’aide ?'])
        Pour comprendre comment traiter vos participations, rendez-vous <a class="link" href="https://reserve-civique.crisp.help/fr/article/comment-gerer-les-participations-des-benevoles-1sizkcs/?bust=1682607862363">ici</a>. Si vous avez toujours des questions, n’hésitez pas à écrire au support en répondant à ce mail. 
    @endcomponent
@endcomponent

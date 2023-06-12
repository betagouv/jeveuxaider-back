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
            <li>{{ $waitingCount }} participation{{ $waitingCount > 1 ? 's' : '' }} en attente depuis plus de 7 jours</li>
            <li>{{ $inProgressCount }} participation{{ $inProgressCount > 1 ? 's' : '' }} en cours depuis plus de 2 mois</li>
        </ul>
    @endcomponent
    @component('mail::components.paragraph')
        En publiant une mission sur JeVeuxAider.gouv.fr, vous vous engagez à répondre aux bénévoles dans un délai de 7 jours, et à valider ou refuser les participations sous 2 mois. Ces participations doivent être mises à jour au plus vite, sans quoi les bénévoles risquent de se désengager, et vous risquez de voir vos missions dépubliées.
    @endcomponent
    @component('mail::button', ['url' => $url])
        Traiter les participations
    @endcomponent
    @component('mail::components.tips', ['title' => 'Besoin d’aide ?'])
        Pour comprendre comment traiter vos participations, rendez-vous <a class="link" href="https://reserve-civique.crisp.help/fr/article/comment-gerer-les-participations-des-benevoles-1sizkcs/?bust=1682607862363">ici</a>. Si vous avez toujours des questions, n’hésitez pas à écrire au support en répondant à ce mail. 
    @endcomponent
@endcomponent

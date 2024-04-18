@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->profile->first_name }} 👋🏻,
    @endcomponent
    @component('mail::components.paragraph')
        <p>Il y a 6 mois, vous vous êtes inscrits sur <a href="{{ $urlHome }}">JeVeuxAider.gouv.fr</a>, la plateforme publique du bénévolat. On se dit que peut être, vous n’avez pas trouvé votre bonheur. 💔 Pourtant on est sûr d’avoir la mission faites pour vous, parmi les 18 000 en ligne, en ce moment même.</p>
        <p>Il suffit de trouver laquelle… En seulement 4 questions, on prend le pari de dénicher le bénévolat qui vous correspondra <strong style="color: #1a1a1a; font-weight: 600;">par-faitement !</strong></p>
    @endcomponent
    @component('mail::components.space', ['height' => 16])
    @endcomponent
    @component('mail::button', ['url' => $urlQuiz])
        Faire le test
    @endcomponent
    @component('mail::components.space', ['height' => 48])
    @endcomponent
    @component('mail::components.tips', ['title' => 'Vous avez des questions ?'])
        N’hésitez pas à écrire au support en retour de ce mail.
    @endcomponent
@endcomponent

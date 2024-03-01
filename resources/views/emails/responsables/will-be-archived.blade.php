@component('mail::message')
    @component('mail::components.headline')
        Ne nous quittez pas 🎵
    @endcomponent
    @component('mail::components.paragraph')
        <p>Bonjour,</p>
        <p>
            Cela va faire presque 3 ans que vous n'êtes plus actif sur la plateforme <a class="link" href="https://www.jeveuxaider.gouv.fr/">JeVeuxAider.gouv.fr</a>. Si vous ne revenez pas sur la plateforme d'ici quelques jours, votre compte sera supprimé. Et ça, ça nous rend triste.
        </p>
        <p>
            D’autant plus que des bénévoles sont toujours prêts à vous aider. On vous invite à poster une mission de bénévolat si vous avez besoin de bénévoles.
        </p>
    @endcomponent
    @component('mail::button', ['url' => $url])
        Poster une mission
    @endcomponent
    @component('mail::components.space', ['height' => 48])
    @endcomponent
    @component('mail::components.paragraph')
        Pour en savoir plus sur l’utilisation de la plateforme, retrouvez toutes les ressources directement sur <a class="link" href="https://reserve-civique.crisp.help/fr/">notre centre d’aide</a>.
    @endcomponent
    @component('mail::components.tips', ['title' => 'Vous avez des questions ?'])
        N’hésitez pas à écrire au support en retour de ce mail.
    @endcomponent
@endcomponent

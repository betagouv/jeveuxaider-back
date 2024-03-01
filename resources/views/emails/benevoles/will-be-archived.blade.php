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
            D'autant plus que des associations, des collectivités territoriales et des acteurs publics ont toujours besoin de votre aide.
        </p>
        <p>
            Du mentorat, des maraudes, des collectes alimentaires, des soins aux animaux, des travaux manuels ou encore des ramassage de déchets, il y a de quoi faire.
        </p>
        <p>
            Retrouvez toutes les façons d'agir directement sur notre plateforme.
        </p>
    @endcomponent
    @component('mail::button', ['url' => $url])
        Voir les façons d'aider
    @endcomponent
    @component('mail::components.tips', ['title' => 'Vous avez des questions ?'])
        N’hésitez pas à écrire au support en retour de ce mail.
    @endcomponent
@endcomponent

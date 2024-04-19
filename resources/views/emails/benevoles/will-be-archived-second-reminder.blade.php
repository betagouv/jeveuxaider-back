@component('mail::message')
    @component('mail::components.headline')
        Tic tac. C’est bientôt la fin 🕰️
    @endcomponent
    @component('mail::components.paragraph')
        <p>
            Sans activité de votre part dans les 7 prochains jours, votre compte <a class="link" href="https://www.jeveuxaider.gouv.fr?utm_source=transactionnel&utm_campaign=app-benevole-archivage-relance">JeVeuxAider.gouv.fr</a> sera supprimé. C’est dommage, des milliers d’organisations ont besoin de bénévoles ! 💪🏻
        </p>
        <p>
            Pour le garder actif, vous pouvez cliquer dans cet email, ou vous connecter directement sur la plateforme <a class="link" href="https://www.jeveuxaider.gouv.fr?utm_source=transactionnel&utm_campaign=app-benevole-archivage-relance">JeVeuxAider.gouv.fr</a>.
        </p>
    @endcomponent
    @component('mail::button', ['url' => $url])
        Garder mon compte actif
    @endcomponent
    @component('mail::components.tips', ['title' => 'Vous avez des questions ?'])
        N’hésitez pas à écrire au support en retour de ce mail.
    @endcomponent
@endcomponent
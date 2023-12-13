@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->profile->first_name }}
    @endcomponent
    @component('mail::components.paragraph')
        Le compte de votre organisation «<strong>{{ $structure->name }}</strong>» a bien été désinscrit de JeVeuxAider.gouv.fr.
    @endcomponent
    @component('mail::components.paragraph')
        Vous conservez en revanche votre accès à la plateforme JeVeuxAider.gouv.fr avec cette adresse en tant que bénévole
        (email et mot de passe identiques).
    @endcomponent
    @component('mail::components.paragraph')
        <p>Vous pouvez :</p>
        <ul>
            <li>soit vous connecter avec cette même adresse email et votre mot de passe <a
                    href="https://www.jeveuxaider.gouv.fr/login">ici</a> pour proposer votre aide pour des missions de bénévolat
            </li>
            <li>soit vous désinscrire également en tant que bénévole (pas à pas <a
                    href="https://reserve-civique.crisp.help/fr/article/comment-me-desinscrire-de-la-plateforme-u0ey89/">ici</a>).
            </li>
        </ul>
    @endcomponent
@endcomponent

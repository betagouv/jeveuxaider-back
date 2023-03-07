@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->profile->first_name }} 👋
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Merci pour votre inscription !'])
        L'heure est venue de trouver votre première mission de bénévolat.
    @endcomponent
    <table cellpadding="0" cellspacing="0" border="0" width="100%"
        style="width: 100% !important; min-width: 100%; max-width: 100%;">
        <tbody>
            <tr>
                <td align="center" valign="top">
                    <!--[if (gte mso 9)|(IE)]>
                            <table border="0" cellspacing="0" cellpadding="0">
                            <tr><td align="center" valign="top" width="325">
                        <![endif]-->
                    <div style="display: inline-block; vertical-align: top; width: 100%; max-width: 325px;">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 88%;">
                            <tbody>
                                <tr>
                                    <td align="left" valign="top">
                                        @component('mail::components.space', ['height' => 35])
                                        @endcomponent
                                        <a href="{{ config('app.front_url') }}/missions-benevolat?domaines=Sport%20pour%20tous"
                                            style="display: block; max-width: 100%;">
                                            <img src="{{ config('app.front_url') }}/images/mail/activites-participez-vie-club-sportif.jpg"
                                                alt="img" width="100%" border="0"
                                                style="display: block; width: 100%;">
                                        </a>
                                        @component('mail::components.space', ['height' => 22])
                                        @endcomponent
                                        <span
                                            style="color: #3f51b5; font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">SPORT
                                            POUR TOUS</span>
                                        @component('mail::components.space', ['height' => 12])
                                        @endcomponent
                                        <a href="{{ config('app.front_url') }}/missions-benevolat?domaines=Sport%20pour%20tous"
                                            style="display: block; max-width: 100%; text-decoration: none;">
                                            <span
                                                style="color: #101010; font-size: 22px; line-height: 30px; font-weight: 400; letter-spacing: 0px;">Participer à l’organisation à la vie d’un club sportif</span>
                                        </a>
                                        @component('mail::components.space', ['height' => 8])
                                        @endcomponent
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td><td align="center" valign="top" width="325"><![endif]-->
                    <div style="display: inline-block; vertical-align: top; width: 100%; max-width: 325px;">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 88%;">
                            <tbody>
                                <tr>
                                    <td align="left" valign="top">
                                        @component('mail::components.space', ['height' => 35])
                                        @endcomponent
                                        <a href="{{ config('app.front_url') }}/missions-benevolat?activity.name=Collecte%20de%20produits"
                                            style="display: block; max-width: 100%;">
                                            <img src="{{ config('app.front_url') }}/images/mail/activites-collectes-produits.jpg"
                                                alt="img" width="100%" border="0"
                                                style="display: block; width: 100%;">
                                        </a>
                                        @component('mail::components.space', ['height' => 22])
                                        @endcomponent
                                        <span
                                            style="color: #FD5655; font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">SOLIDARITÉ
                                            ET INSERTION</span>
                                        @component('mail::components.space', ['height' => 12])
                                        @endcomponent
                                        <a href="{{ config('app.front_url') }}/missions-benevolat?activity.name=Collecte%20de%20produits"
                                            style="display: block; max-width: 100%; text-decoration: none;">
                                            <span
                                                style="color: #101010; font-size: 22px; line-height: 30px; font-weight: 400; letter-spacing: 0px;">Collectez
                                                des produits pour lutter contre le gaspillage alimentaire</span>
                                        </a>
                                        @component('mail::components.space', ['height' => 8])
                                        @endcomponent
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]>
                            </td></tr>
                            </table>
                        <![endif]-->
                </td>
            </tr>
        </tbody>
    </table>
    <table cellpadding="0" cellspacing="0" border="0" width="100%"
        style="width: 100% !important; min-width: 100%; max-width: 100%;">
        <tbody>
            <tr>
                <td align="center" valign="top">
                    <!--[if (gte mso 9)|(IE)]>
                            <table border="0" cellspacing="0" cellpadding="0">
                            <tr><td align="center" valign="top" width="325">
                        <![endif]-->
                    <div style="display: inline-block; vertical-align: top; width: 100%; max-width: 325px;">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 88%;">
                            <tbody>
                                <tr>
                                    <td align="left" valign="top">
                                        @component('mail::components.space', ['height' => 35])
                                        @endcomponent
                                        <a href="{{ config('app.front_url') }}/missions-benevolat?activity.name=Mentorat%20%26%20Parrainage"
                                            style="display: block; max-width: 100%;">
                                            <img src="{{ config('app.front_url') }}/images/mail/activites-devenir-mentor.jpg"
                                                alt="img" width="100%" border="0"
                                                style="display: block; width: 100%;">
                                        </a>
                                        @component('mail::components.space', ['height' => 22])
                                        @endcomponent
                                        <span
                                            style="color: #3f51b5; font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">ÉDUCATION
                                            POUR TOUS</span>
                                        @component('mail::components.space', ['height' => 12])
                                        @endcomponent
                                        <a href="{{ config('app.front_url') }}/missions-benevolat?activity.name=Mentorat%20%26%20Parrainage"
                                            style="display: block; max-width: 100%; text-decoration: none;">
                                            <span
                                                style="color: #101010; font-size: 22px; line-height: 30px; font-weight: 400; letter-spacing: 0px;">Devenez
                                                mentor d'une personne en difficulté</span>
                                        </a>
                                        @component('mail::components.space', ['height' => 8])
                                        @endcomponent
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td><td align="center" valign="top" width="325"><![endif]-->
                    <div style="display: inline-block; vertical-align: top; width: 100%; max-width: 325px;">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 88%;">
                            <tbody>
                                <tr>
                                    <td align="left" valign="top">
                                        @component('mail::components.space', ['height' => 35])
                                        @endcomponent
                                        <a href="{{ config('app.front_url') }}/missions-benevolat?domaines=Protection%20de%20la nature"
                                            style="display: block; max-width: 100%;">
                                            <img src="{{ config('app.front_url') }}/images/mail/activites-protection-nature.jpg"
                                                alt="img" width="100%" border="0"
                                                style="display: block; width: 100%;">
                                        </a>
                                        @component('mail::components.space', ['height' => 22])
                                        @endcomponent
                                        <span
                                            style="color: #FD5655; font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">PROTECTION DE LA NATURE</span>
                                        @component('mail::components.space', ['height' => 12])
                                        @endcomponent
                                        <a href="{{ config('app.front_url') }}/missions-benevolat?domaines=Protection%20de%20la nature"
                                            style="display: block; max-width: 100%; text-decoration: none;">
                                            <span
                                                style="color: #101010; font-size: 22px; line-height: 30px; font-weight: 400; letter-spacing: 0px;">Je contribue à la protection de l’environnement</span>
                                        </a>
                                        @component('mail::components.space', ['height' => 8])
                                        @endcomponent
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]>
                            </td></tr></table>
                        <![endif]-->
                </td>
            </tr>
        </tbody>
    </table>
    @component('mail::components.space', ['height' => 33])
    @endcomponent
    <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88%;">
        <tbody>
            <tr>
                <td align="left" valign="top">
                    @component('mail::button', ['url' => $url])
                        Trouver votre mission
                    @endcomponent
                </td>
            </tr>
        </tbody>
    </table>
    @component('mail::components.tips', ['title' => 'Comment trouver une mission ?'])
        Rien de plus simple, il suffit de vous rendre sur JeVeuxAider.gouv.fr et de renseigner votre ville ou département. Vous
        pouvez ensuite trier selon vos centres d’intérêt et lancer la recherche pour faire votre choix parmi de nombreuses
        missions de bénévolat.
        Si vous avez un doute, <a class="link"
            href="https://www.youtube.com/watch?v=R-gEYk-06I4&ab_channel=JeVeuxAider-gouv-fr">regardez
            notre vidéo ›</a>
    @endcomponent
@endcomponent

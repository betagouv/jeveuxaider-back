@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->profile->first_name }} 👋
    @endcomponent
    @component('mail::components.headline')
        Merci pour votre inscription !<br>
        L'heure est venue de trouver votre première mission de bénévolat.
    @endcomponent
    <table cellpadding="0" cellspacing="0" border="0" width="100%"
        style="width: 100% !important; min-width: 100%; max-width: 100%;">
        <tbody>
            <tr>
                <td align="center" valign="top">
                    <!--[if (gte mso 9)|(IE)]>
                                <table border="0" cellspacing="0" cellpadding="0">
                                <tr><td align="center" valign="top" width="325"><![endif]-->
                    <div class="mob_btn" style="display: inline-block; vertical-align: top; width: 325px;">
                        <table class="mob_card" cellpadding="0" cellspacing="0" border="0" width="295"
                            style="width: 295px !important; min-width: 295px; max-width: 295px;">
                            <tbody>
                                <tr>
                                    <td align="left" valign="top">
                                        <div style="height: 35px; line-height: 35px; font-size: 33px;">&nbsp;</div>
                                        <a href="https://www.jeveuxaider.gouv.fr/missions-benevolat?activity.name=Alphab%C3%A9tisation%20%2F%20Apprentissage%20du%20fran%C3%A7ais%20%28FLE%29"
                                            target="_blank" style="display: block; max-width: 100%;">
                                            <img src="{{ config('app.front_url') }}/images/mail/activites-enseignement-francais.jpg"
                                                alt="img" width="100%" border="0"
                                                style="display: block; width: 100%;">
                                        </a>
                                        <div style="height: 22px; line-height: 22px; font-size: 20px;">&nbsp;</div>
                                        <font face="'Source Sans Pro', sans-serif" color="#3f51b5"
                                            style="font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">
                                            <span
                                                style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #3f51b5; font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">EDUCATION
                                                POUR TOUS</span>
                                        </font>
                                        <div style="height: 12px; line-height: 12px; font-size: 10px;">&nbsp;</div>
                                        <font face="'Source Sans Pro', sans-serif" color="#101010"
                                            style="font-size: 26px; line-height: 33px; font-weight: 300; letter-spacing: -1px;">
                                            <span
                                                style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #101010; font-size: 26px; line-height: 33px; font-weight: 400; letter-spacing: 0px;">Enseignez
                                                le français comme langue étrangère</span>
                                        </font>
                                        <div style="height: 8px; line-height: 8px; font-size: 6px;">&nbsp;</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td><td align="center" valign="top" width="325"><![endif]-->
                    <div class="mob_btn" style="display: inline-block; vertical-align: top; width: 325px;">
                        <table class="mob_card" cellpadding="0" cellspacing="0" border="0" width="295"
                            style="width: 295px !important; min-width: 295px; max-width: 295px;">
                            <tbody>
                                <tr>
                                    <td align="left" valign="top">
                                        <div style="height: 35px; line-height: 35px; font-size: 33px;">&nbsp;</div>
                                        <a href="https://www.jeveuxaider.gouv.fr/missions-benevolat?activity.name=Collecte%20de%20produits"
                                            target="_blank" style="display: block; max-width: 100%;">
                                            <img src="{{ config('app.front_url') }}/images/mail/activites-collectes-produits.jpg"
                                                alt="img" width="100%" border="0"
                                                style="display: block; width: 100%;">
                                        </a>
                                        <div style="height: 22px; line-height: 22px; font-size: 20px;">&nbsp;</div>
                                        <font face="'Source Sans Pro', sans-serif" color="#FD5655"
                                            style="font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">
                                            <span
                                                style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #FD5655; font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">SOLIDARITÉ
                                                ET INSERTION</span>
                                        </font>
                                        <div style="height: 12px; line-height: 12px; font-size: 10px;">&nbsp;</div>
                                        <font face="'Source Sans Pro', sans-serif" color="#101010"
                                            style="font-size: 26px; line-height: 33px; font-weight: 300; letter-spacing: -1px;">
                                            <span
                                                style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #101010; font-size: 26px; line-height: 33px; font-weight: 400; letter-spacing: 0px;">Collectez
                                                des produits pour lutter contre le gaspillage alimentaire</span>
                                        </font>
                                        <div style="height: 8px; line-height: 8px; font-size: 6px;">&nbsp;</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]>
                                </td></tr>
                                </table><![endif]-->
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
                                <tr><td align="center" valign="top" width="325"><![endif]-->
                    <div class="mob_btn" style="display: inline-block; vertical-align: top; width: 325px;">
                        <table class="mob_card" cellpadding="0" cellspacing="0" border="0" width="295"
                            style="width: 295px !important; min-width: 295px; max-width: 295px;">
                            <tbody>
                                <tr>
                                    <td align="left" valign="top">
                                        <div style="height: 35px; line-height: 35px; font-size: 33px;">&nbsp;</div>
                                        <a href="https://www.jeveuxaider.gouv.fr/missions-benevolat?activity.name=Mentorat%20%26%20Parrainage"
                                            target="_blank" style="display: block; max-width: 100%;">
                                            <img src="{{ config('app.front_url') }}/images/mail/activites-devenir-mentor.jpg"
                                                alt="img" width="100%" border="0"
                                                style="display: block; width: 100%;">
                                        </a>
                                        <div style="height: 22px; line-height: 22px; font-size: 20px;">&nbsp;</div>
                                        <font face="'Source Sans Pro', sans-serif" color="#3f51b5"
                                            style="font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">
                                            <span
                                                style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #3f51b5; font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">EDUCATION
                                                POUR TOUS</span>
                                        </font>
                                        <div style="height: 12px; line-height: 12px; font-size: 10px;">&nbsp;</div>
                                        <font face="'Source Sans Pro', sans-serif" color="#101010"
                                            style="font-size: 26px; line-height: 33px; font-weight: 300; letter-spacing: -1px;">
                                            <span
                                                style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #101010; font-size: 26px; line-height: 33px; font-weight: 400; letter-spacing: 0px;">Devenez
                                                un mentor d'une personne en difficulté</span>
                                        </font>
                                        <div style="height: 8px; line-height: 8px; font-size: 6px;">&nbsp;</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]></td><td align="center" valign="top" width="325"><![endif]-->
                    <div class="mob_btn" style="display: inline-block; vertical-align: top; width: 325px;">
                        <table class="mob_card" cellpadding="0" cellspacing="0" border="0" width="295"
                            style="width: 295px !important; min-width: 295px; max-width: 295px;">
                            <tbody>
                                <tr>
                                    <td align="left" valign="top">
                                        <div style="height: 35px; line-height: 35px; font-size: 33px;">&nbsp;</div>
                                        <a href="https://www.jeveuxaider.gouv.fr/missions-benevolat?activity.name=Lutte%20contre%20l%27isolement"
                                            target="_blank" style="display: block; max-width: 100%;">
                                            <img src="{{ config('app.front_url') }}/images/mail/activites-luttez-contre-isolement.jpg"
                                                alt="img" width="100%" border="0"
                                                style="display: block; width: 100%;">
                                        </a>
                                        <div style="height: 22px; line-height: 22px; font-size: 20px;">&nbsp;</div>
                                        <font face="'Source Sans Pro', sans-serif" color="#FD5655"
                                            style="font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">
                                            <span
                                                style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #FD5655; font-size: 16px; line-height: 22px; font-weight: 700; text-transform: uppercase;">SOLIDARITÉ
                                                ET INSERTION</span>
                                        </font>
                                        <div style="height: 12px; line-height: 12px; font-size: 10px;">&nbsp;</div>
                                        <font face="'Source Sans Pro', sans-serif" color="#101010"
                                            style="font-size: 26px; line-height: 33px; font-weight: 300; letter-spacing: -1px;">
                                            <span
                                                style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #101010; font-size: 26px; line-height: 33px; font-weight: 400; letter-spacing: 0px;">Luttez
                                                contre l'isolement des personnes fragiles</span>
                                        </font>
                                        <div style="height: 8px; line-height: 8px; font-size: 6px;">&nbsp;</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--[if (gte mso 9)|(IE)]>
                                </td></tr>
                                </table><![endif]-->
                </td>
            </tr>
        </tbody>
    </table>
    <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
    @component('mail::button', ['url' => $url])
        Trouver votre mission
    @endcomponent
    @component('mail::components.tips', ['title' => 'Comment trouver une mission ?'])
        Rien de plus simple, il suffit de vous rendre sur JeVeuxAider.gouv.fr et de renseigner votre ville ou département. Vous
        pouvez ensuite trier selon vos centres d’intérêt et lancer la recherche pour faire votre choix parmi de nombreuses
        missions de bénévolat.
        Si vous avez un doute, <a class="link" href="#" target="_blank">regardez notre vidéo ›</a>
    @endcomponent
@endcomponent

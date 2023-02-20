<table cellpadding="0" cellspacing="0" border="0" width="100%"
    style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
    <tr>
        <td align="center" valign="top">
            @component('mail::components.space', ['height' => 34, 'color' => '#f3f3f3'])
            @endcomponent
            <table cellpadding="0" cellspacing="0" border="0" width="88%"
                style="width: 88% !important; min-width: 88%; max-width: 88%;">
                <tr>
                    <td class="footer-links-wrapper" align="center" valign="top">
                        <a href="https://www.jeveuxaider.gouv.fr/missions-benevolat">
                            Trouver une mission
                        </a>
                        <span class="bull">&bull;</span>
                        <a href="https://reserve-civique.crisp.help/fr/">
                            Foire aux questions
                        </a>
                        <span class="bull">&bull;</span>
                        <a href="https://www.jeveuxaider.gouv.fr/profile">
                            Mon compte
                        </a>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        @component('mail::components.space', ['height' => 35, 'color' => '#f3f3f3'])
                        @endcomponent
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center" valign="top">
                                    <a href="https://www.instagram.com/jeveuxaider_gouv/?hl=fr"
                                        style="display: block; max-width: 19px;">
                                        <img src="{{ config('app.front_url') }}/images/mail/instagram.png"
                                            alt="img" width="19" border="0"
                                            style="display: block; width: 19px;" />
                                    </a>
                                </td>
                                <td width="45" style="width: 45px; max-width: 45px; min-width: 45px;">
                                    &nbsp;</td>
                                <td align="center" valign="top">
                                    <a href="https://fr-fr.facebook.com/jeveuxaider.gouv.fr/"
                                        style="display: block; max-width: 18px;">
                                        <img src="{{ config('app.front_url') }}/images/mail/facebook.png" alt="img"
                                            width="18" border="0" style="display: block; width: 18px;" />
                                    </a>
                                </td>
                                <td width="45" style="width: 45px; max-width: 45px; min-width: 45px;">
                                    &nbsp;</td>
                                <td align="center" valign="top">
                                    <a href="https://twitter.com/ReserveCivique"
                                        style="display: block; max-width: 21px;">
                                        <img src="{{ config('app.front_url') }}/images/mail/twitter.png" alt="img"
                                            width="21" border="0" style="display: block; width: 21px;" />
                                    </a>
                                </td>
                                <td width="45" style="width: 45px; max-width: 45px; min-width: 45px;">
                                    &nbsp;</td>
                                <td align="center" valign="top">
                                    <a href="https://fr.linkedin.com/company/jeveuxaider-gouv-fr"
                                        style="display: block; max-width: 25px;">
                                        <img src="{{ config('app.front_url') }}/images/mail/linkedin.png" alt="img"
                                            width="18" border="0" style="display: block; width: 18px;" />
                                    </a>
                                </td>
                            </tr>
                        </table>
                        @component('mail::components.space', ['height' => 35, 'color' => '#f3f3f3'])
                        @endcomponent
                        <div style="color: #868686; font-size: 17px; line-height: 20px; max-width: 300px;">
                            Cet e-mail a été envoyé suite à votre inscription sur JeVeuxAider.gouv.fr
                        </div>
                        @component('mail::components.space', ['height' => 35, 'color' => '#f3f3f3'])
                        @endcomponent
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

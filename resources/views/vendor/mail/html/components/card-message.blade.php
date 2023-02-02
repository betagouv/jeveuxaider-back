<div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
<table cellpadding="0" cellspacing="0" border="0" width="88%"
    style="width: 88% !important; min-width: 88%; max-width: 88%; background: #e5e4f6; border-top-left-radius: 8px; border-top-right-radius: 8px;">
    <tbody>
        <tr>
            <td width="10" style="width: 10px; max-width: 10px; min-width: 10px;">&nbsp;</td>
            <td align="center" valign="top">
                <div style="height: 8px; line-height: 8px; font-size: 6px;">&nbsp;</div>
                <!--[if (gte mso 9)|(IE)]>
          <table border="0" cellspacing="0" cellpadding="0">
          <tr><td align="center" valign="top" width="65"><![endif]-->
                <div style="display: inline-block; vertical-align: top; width: 65px;">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%"
                        style="width: 100% !important; min-width: 100%; max-width: 100%;">
                        <tbody>
                            <tr>
                                <td align="center" valign="top">
                                    <div style="height: 10px; line-height: 10px; font-size: 8px;">&nbsp;</div>
                                    <span style="display: block; max-width: 50px;">
                                        <img src="{{ config('app.front_url') }}/images/mail/user.jpg" alt="img"
                                            width="50" border="0"
                                            style="display: block; width: 50px;border-radius:100px">
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]></td><td align="left" valign="top" width="515"><![endif]-->
                <div style="display: inline-block; vertical-align: top; width: 88%; min-width: 260px;">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%"
                        style="width: 100% !important; min-width: 100%; max-width: 100%;">
                        <tbody>
                            <tr>
                                <td width="6" style="width: 6px; max-width: 6px; min-width: 6px;">&nbsp;</td>
                                <td class="mob_center" align="left" valign="top">
                                    <div style="height: 10px; line-height: 10px; font-size: 8px;">&nbsp;</div>
                                    <a href="#"
                                        style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 19px; line-height: 25px; font-weight: 600; text-decoration: none;">
                                        <font face="'Source Sans Pro', sans-serif" color="#1a1a1a"
                                            style="font-size: 19px; line-height: 25px; font-weight: 600; text-decoration: none;">
                                            <span
                                                style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 19px; line-height: 25px; font-weight: 600; text-decoration: none;">{{ $title }}</span>
                                        </font>
                                    </a>
                                    <div style="height: 2px; line-height: 2px; font-size: 1px;">&nbsp;</div>
                                    <a href="#"
                                        style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #888888; font-size: 19px; line-height: 22px; text-decoration: none;">
                                        <font face="'Source Sans Pro', sans-serif" color="#888888"
                                            style="font-size: 19px; line-height: 22px; text-decoration: none;">
                                            <span
                                                style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #302e6c; font-size: 19px; line-height: 22px; text-decoration: none;">{{ $subtitle ?? '' }}</span>
                                        </font>
                                    </a>
                                </td>
                                <td width="6" style="width: 6px; max-width: 6px; min-width: 6px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--[if (gte mso 9)|(IE)]>
          </td></tr>
          </table><![endif]-->
                <div style="height: 18px; line-height: 18px; font-size: 16px;">&nbsp;</div>
            </td>
            <td width="10" style="width: 10px; max-width: 10px; min-width: 10px;">&nbsp;</td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="88%"
    style="width: 88% !important; min-width: 88%; max-width: 88%; border-width: 1px; border-style: solid; border-color: #e5e5e5; border-top: none;">
    <tbody>
        <tr>
            <td width="5%" style="width: 5%; max-width: 5%; min-width: 5%;">&nbsp;</td>
            <td align="left" valign="top">
                <div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
                <font class="mob_txt" face="'Source Sans Pro', sans-serif" color="#1a1a1a"
                    style="font-size: 22px; line-height: 27px;">
                    <span class="mob_txt"
                        style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 22px; line-height: 36px; font-style: italic;">
                        "{{ $slot }}"
                    </span>
                </font>
                <div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
                @isset($footer)
                    {{ $footer }}
                @endisset
            </td>
            <td width="5%" style="width: 5%; max-width: 5%; min-width: 5%;">&nbsp;</td>
        </tr>
    </tbody>
</table>

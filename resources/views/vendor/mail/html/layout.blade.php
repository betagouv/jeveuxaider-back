<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style type="text/css">
        html {
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }

        @media only screen and (min-device-width: 750px) {
            .table750 {
                width: 750px !important;
            }
        }

        @media only screen and (max-device-width: 750px),
        only screen and (max-width: 750px) {
            table[class="table750"] {
                width: 100% !important;
            }

            .mob_b {
                width: 93% !important;
                max-width: 93% !important;
                min-width: 93% !important;
            }

            .mob_b1 {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 100% !important;
            }

            .mob_left {
                text-align: left !important;
            }

            .mob_soc {
                width: 50% !important;
                max-width: 50% !important;
                min-width: 50% !important;
            }

            .mob_menu {
                width: 50% !important;
                max-width: 50% !important;
                min-width: 50% !important;
                box-shadow: inset -1px -1px 0 0 rgba(255, 255, 255, 0.2);
            }

            .mob_center {
                text-align: center !important;
            }

            .top_pad {
                height: 15px !important;
                max-height: 15px !important;
                min-height: 15px !important;
            }

            .mob_pad {
                width: 15px !important;
                max-width: 15px !important;
                min-width: 15px !important;
            }

            .mob_div {
                display: block !important;
            }
        }

        @media only screen and (max-device-width: 550px),
        only screen and (max-width: 550px) {
            .mod_div {
                display: block !important;
            }
        }

        .table750 {
            width: 750px;
        }
    </style>
</head>

<body style="margin: 0; padding: 0;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%"
        style="background: #f3f3f3; font-size: 1px; line-height: normal;">
        <tr>
            <td align="center" valign="top">
                <!--[if (gte mso 9)|(IE)]>
            <table border="0" cellspacing="0" cellpadding="0">
            <tr><td align="center" valign="top" width="750"><![endif]-->
                <table cellpadding="0" cellspacing="0" border="0" width="750" class="table750"
                    style="width: 100%; max-width: 750px; background: #f3f3f3;">
                    <tr>
                        <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;
                        </td>
                        <td align="center" valign="top" style="background: #ffffff;">

                            {{ $header ?? '' }}

                            {{ Illuminate\Mail\Markdown::parse($slot) }}

                            <table cellpadding="0" cellspacing="0" border="0" width="90%"
                                style="width: 90% !important; min-width: 90%; max-width: 90%; border-width: 1px; border-style: solid; border-color: #e8e8e8; border-bottom: none; border-left: none; border-right: none;">
                                <tr>
                                    <td align="left" valign="top">
                                        <div style="height: 24px; line-height: 24px; font-size: 24px;">&nbsp;</div>
                                    </td>
                                </tr>
                            </table>

                            <table cellpadding="0" cellspacing="0" border="0" width="88%"
                                style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                <tr>
                                    <td align="center" valign="top">
                                        <font face="'Source Sans Pro', sans-serif" color="#585858"
                                            style="font-size: 24px; line-height: 32px;"> <span
                                                style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #585858; font-size: 24px; line-height: 32px;">À
                                                bientôt <br>
                                                L’équipe de <a href="https://www.jeveuxaider.gouv.fr/"
                                                    style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 24px; line-height: 32px; text-decoration: none; font-weight: 600">JeVeuxAider.gouv.fr</a></span>
                                        </font>
                                        <div style="height: 28px; line-height: 28px; font-size: 26px;">&nbsp;</div>
                                    </td>
                                </tr>
                            </table>


                            {{ $footer ?? '' }}

                        </td>
                        <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">
                            &nbsp;</td>
                    </tr>
                </table>
                <!--[if (gte mso 9)|(IE)]>
            </td></tr>
            </table><![endif]-->
            </td>
        </tr>
    </table>
    {{-- <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                    {{ $header ?? '' }}

                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                                role="presentation">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell">
                                        {{ Illuminate\Mail\Markdown::parse($slot) }}

                                        {{ $subcopy ?? '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{ $footer ?? '' }}
                </table>
            </td>
        </tr>
    </table> --}}
</body>

</html>

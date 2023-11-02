<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <!--[if mso]>
    <style type="text/css">
        table {border-collapse:collapse; border-spacing:0; margin:0}
        div, td {padding:0;}
        div {margin:0 !important;}
    </style>
    <![endif]-->

    <style>
        @media screen and (max-width:600px) {
            .footer-links-wrapper a { display: block !important; }
            .footer-links-wrapper .bull { display: none !important; }
        }
    </style>
</head>

<body style="margin: 0; padding: 0;">
    <table class="mail-body" cellpadding="0" cellspacing="0" border="0" width="100%"
        style="background: #f3f3f3; line-height: normal;">
        <tr>
            <td align="center" valign="top">
                <!--[if (gte mso 9)|(IE)]>
            <table border="0" cellspacing="0" cellpadding="0">
            <tr><td align="center" valign="top" width="750"><![endif]-->
                <table cellpadding="0" cellspacing="0" border="0" width="750" class="table750"
                    style="width: 100%; max-width: 750px; background: #f3f3f3;">
                    <tr>
                        <td class="mob_pad" width="16" style="width: 16px; max-width: 16px; min-width: 16px;">&nbsp;
                        </td>
                        <td align="center" valign="top" style="background: #ffffff;">

                            {{ $header ?? '' }}

                            {{ Illuminate\Mail\Markdown::parse($slot) }}

                            <table cellpadding="0" cellspacing="0" border="0" width="90%"
                                style="width: 90% !important; min-width: 90%; max-width: 90%; border-width: 1px; border-style: solid; border-color: #e8e8e8; border-bottom: none; border-left: none; border-right: none;">
                                <tr>
                                    <td align="left" valign="top">
                                        @component('mail::components.space', ['height' => 24])
                                        @endcomponent
                                    </td>
                                </tr>
                            </table>

                            <table cellpadding="0" cellspacing="0" border="0" width="88%"
                                style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                <tr>
                                    <td align="center" valign="top">
                                        {{ $signature ?? '' }}
                                        @component('mail::components.space', ['height' => 28])
                                        @endcomponent
                                    </td>
                                </tr>
                            </table>


                            {{ $footer ?? '' }}

                        </td>
                        <td class="mob_pad" width="16" style="width: 16px; max-width: 16px; min-width: 16px;">
                            &nbsp;</td>
                    </tr>
                </table>
                <!--[if (gte mso 9)|(IE)]>
            </td></tr>
            </table><![endif]-->
            </td>
        </tr>
    </table>
</body>

</html>

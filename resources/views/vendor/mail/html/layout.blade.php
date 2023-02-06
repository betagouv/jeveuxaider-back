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
                        <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;
                        </td>
                        <td align="center" valign="top" style="background: #ffffff;">

                            {{ $header ?? '' }}

                            {{ Illuminate\Mail\Markdown::parse($slot) }}

                            <table cellpadding="0" cellspacing="0" border="0" width="90%"
                                style="width: 90% !important; min-width: 90%; max-width: 90%; border-width: 1px; border-style: solid; border-color: #e8e8e8; border-bottom: none; border-left: none; border-right: none;">
                                <tr>
                                    <td align="left" valign="top">
                                        @component('mail::components.space', ['height' => 24])@endcomponent
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
                                        @component('mail::components.space', ['height' => 28])@endcomponent
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

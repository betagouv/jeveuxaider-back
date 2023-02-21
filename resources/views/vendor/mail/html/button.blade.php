<table cellpadding="0" cellspacing="0" border="0" width="100%"
    style="width: 100% !important; min-width: 100%; max-width: 100%;">
    <tbody>
        <tr>
            <td align="{{ $align ?? 'center' }}" valign="top">
                <table class="btn-wrapper" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td align="center" valign="middle">
                                <a class="button" href="{{ $url }}">
                                    <!--[if mso]>
                                    <i style="letter-spacing: 25px; mso-font-width: -100%; mso-text-raise: 30pt;">&nbsp;</i>
                                    <![endif]-->
                                    {{ $slot }}
                                    <!--[if mso]>
                                    <i style="letter-spacing: 25px; mso-font-width: -100%;">&nbsp;</i>
                                    <![endif]-->
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
@isset($subtitle)
    <table cellpadding="0" cellspacing="0" border="0" width="100%"
        style="width: 100% !important; min-width: 100%; max-width: 100%;">
        <tbody>
            <tr>
                <td align="{{ $align ?? 'center' }}" valign="top">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <td align="center" valign="middle">
                                    @component('mail::components.space', ['height' => 8])
                                    @endcomponent
                                    <span style="color: #8c8c8c; font-size: 19px!important; line-height: 23px;">
                                        {{ $subtitle }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
@endisset

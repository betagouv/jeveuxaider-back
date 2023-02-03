<table cellpadding="0" cellspacing="0" border="0" width="100%"
    style="width: 100% !important; min-width: 100%; max-width: 100%;">
    <tbody>
        <tr>
            <td align="{{ $align ?? 'center' }}" valign="top">
                <table class="btn-wrapper" cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td align="center" valign="middle" style="padding: 16px 24px">
                                <a class="button" href="{{ $url }}">
                                    {{ $slot }}
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

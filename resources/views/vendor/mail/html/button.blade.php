<table cellpadding="0" cellspacing="0" border="0" width="100%"
    style="width: 100% !important; min-width: 100%; max-width: 100%;">
    <tbody>
        <tr>
            <td align="{{ $align ?? 'center' }}" valign="top">
                <table class="mob_btn" cellpadding="0" cellspacing="0" border="0" width="300"
                    style="width: 300px !important; max-width: 300px; min-width: 300px; background: #070191; ">
                    <tbody>
                        <tr>
                            <td align="center" valign="middle" height="60">
                                <a href="{{ $url }}" target="_blank"
                                    style="display: block; width: 100%; height: 60px; font-family: 'Source Sans Pro', Arial, Verdana, Tahoma, Geneva, sans-serif; color: #ffffff; font-size: 20px; line-height: 60px; text-decoration: none; white-space: nowrap; font-weight: 600;">
                                    <font face="'Source Sans Pro', sans-serif" color="#ffffff"
                                        style="font-size: 20px; line-height: 60px; text-decoration: none; white-space: nowrap; font-weight: 600;">
                                        <span
                                            style="font-family: 'Source Sans Pro', Arial, Verdana, Tahoma, Geneva, sans-serif; color: #ffffff; font-size: 20px; line-height: 60px; text-decoration: none; white-space: nowrap; font-weight: 400;">{{ $slot }}</span>
                                    </font>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

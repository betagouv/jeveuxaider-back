<table cellpadding="0" cellspacing="0" border="0" width="88%"
    style="width: 88% !important; min-width: 88%; max-width: 88%;">
    <tr>
        <td align="left" valign="top">
            @isset($title)
                <font face="'Source Sans Pro', sans-serif" color="#1a1a1a" style="font-size: 24px; line-height: 32px;">
                    <span
                        style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 24px; line-height: 40px;font-weight: 600;">
                        {{ $title }}
                    </span>
                </font>
            @endisset
            <p style="margin-bottom: 24px;">
                <font face="'Source Sans Pro', sans-serif" color="#585858" style="font-size: 24px; line-height: 32px;">
                    <span
                        style="font-family: 'Source Sans Pro', Arial, Tahoma, Geneva, sans-serif; color: #585858; font-size: 24px; line-height: 32px;">
                        {{ $slot }}
                    </span>
                </font>
            </p>
        </td>
    </tr>
</table>

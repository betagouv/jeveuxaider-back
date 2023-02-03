<table cellpadding="0" cellspacing="0" border="0" width="88%"
    style="width: 88% !important; min-width: 88%; max-width: 88%;">
    <tr>
        <td class="paragraph-wrapper" align="left" valign="top">
            @isset($title)
                <div class="title">{{ $title }}</div>
            @endisset
            <div class="content">
                {{ $slot }}
            </div>
            <div style="height: 24px; line-height: 24px; font-size: 22px;">&nbsp;</div>
        </td>
    </tr>
</table>

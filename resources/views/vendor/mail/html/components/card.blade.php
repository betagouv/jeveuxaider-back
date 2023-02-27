<table cellpadding="0" cellspacing="0" border="0" width="70%"
    style="width: 70% !important; min-width: 270px; max-width: 70%; border-width: 1px; border-style: solid; border-color: #e5e5e5; border-radius: 5px;">
    <tbody>
        <tr>
            <td width="15" style="width: 15px; max-width: 15px; min-width: 15px;">&nbsp;</td>
            <td align="center" valign="top">
                @component('mail::components.space', ['height' => 32])
                @endcomponent
                {{ $slot }}
                @component('mail::components.space', ['height' => 8])
                @endcomponent
            </td>
            <td width="15" style="width: 15px; max-width: 15px; min-width: 15px;">&nbsp;</td>
        </tr>
    </tbody>
</table>

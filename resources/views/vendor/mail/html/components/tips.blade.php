@component('mail::components.space', ['height' => 38])@endcomponent
<table cellpadding="0" cellspacing="0" border="0" width="88%"
    style="width: 88% !important; min-width: 88%; max-width: 88%; border-width: 1px; border-style: solid; border-color: #e8e8e8; border-bottom: none; border-left: none; border-right: none;">
    <tbody>
        <tr>
            <td class="tips-wrapper" align="left" valign="top">
                @component('mail::components.space', ['height' => 38])@endcomponent
                <div class="title">{{ $title ?? 'Astuces' }}</div>
                @component('mail::components.space', ['height' => 14])@endcomponent
            </td>
        </tr>
    </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="88%"
    style="width: 88% !important; min-width: 88%; max-width: 88%; background: #070191; font-size: 1px; border-top-left-radius: 8px; border-top-right-radius: 8px;">
    <tbody>
        <tr>
            <td width="10" style="width: 10px; max-width: 10px; min-width: 10px;">&nbsp;</td>
            <td align="center" valign="top">
                @component('mail::components.space', ['height' => 10, 'color' => '#070191'])@endcomponent
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
                @component('mail::components.space', ['height' => 24])@endcomponent
                <div class="tips-wrapper-content">{{ $slot }}</div>
                @component('mail::components.space', ['height' => 24])@endcomponent
            </td>
            <td width="5%" style="width: 5%; max-width: 5%; min-width: 5%;">&nbsp;</td>
        </tr>
    </tbody>
</table>

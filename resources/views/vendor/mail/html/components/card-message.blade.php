@component('mail::components.space', ['height' => 24])
@endcomponent
<table cellpadding="0" cellspacing="0" border="0" width="88%"
    style="width: 88% !important; min-width: 88%; max-width: 88%; background: #e5e4f6; border-top-left-radius: 8px; border-top-right-radius: 8px;">
    <tbody>
        <tr>
            <td width="10" style="width: 10px; max-width: 10px; min-width: 10px;">&nbsp;</td>
            <td valign="top">
                @component('mail::components.space', ['height' => 8])
                @endcomponent
                <table cellpadding="0" cellspacing="0" border="0" width="100%"
                    style="width: 100% !important; min-width: 100%; max-width: 100%;">
                    <tbody>
                        <tr>
                            <td width="65" align="center" valign="top">
                                @component('mail::components.space', ['height' => 10])
                                @endcomponent
                                <span style="display: block; max-width: 50px;">
                                    <img src="{{ config('app.front_url') }}/images/mail/user.jpg" alt="img"
                                        width="50" border="0"
                                        style="display: block; width: 50px;border-radius:100px">
                                </span>
                            </td>
                            <td width="6" style="width: 6px; max-width: 6px; min-width: 6px;">&nbsp;</td>
                            <td class="mob_center" align="left" valign="top">
                                @component('mail::components.space', ['height' => 10])
                                @endcomponent
                                <div style="color: #1a1a1a; font-size: 19px; font-weight: 600;">{{ $title }}
                                </div>
                                @if (isset($subtitle))
                                    <div style="color: #302e6c; font-size: 19px;">{{ $subtitle }}</div>
                                @endif
                            </td>
                            <td width="6" style="width: 6px; max-width: 6px; min-width: 6px;">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                @component('mail::components.space', ['height' => 18])
                @endcomponent
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
                @component('mail::components.space', ['height' => 24])
                @endcomponent
                <div class="mob_txt" style="color: #1a1a1a; font-size: 22px; line-height: 36px; font-style: italic;">
                    "{{ $slot }}"
                </div>
                @component('mail::components.space', ['height' => 24])
                @endcomponent
                @isset($footer)
                    {{ $footer }}
                @endisset
            </td>
            <td width="5%" style="width: 5%; max-width: 5%; min-width: 5%;">&nbsp;</td>
        </tr>
    </tbody>
</table>

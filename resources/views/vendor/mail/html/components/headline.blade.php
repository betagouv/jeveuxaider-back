<table cellpadding="0" cellspacing="0" border="0" width="88%"
    style="width: 88% !important; min-width: 88%; max-width: 88%;">
    <tbody>
        <tr>
            <td align="{{ $align ?? 'left' }}" class="headline-wrapper" align="left" valign="top">
                <div class="headline"> {{ $slot }}</div>
                @isset($subtitle)
                    <div class="subtitle">{{ $subtitle }}</div>
                @endisset
            </td>
        </tr>
    </tbody>
</table>
@component('mail::components.space', ['height' => 33])
@endcomponent

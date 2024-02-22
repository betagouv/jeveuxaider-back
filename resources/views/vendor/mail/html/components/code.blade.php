@component('mail::components.space', ['height' => 36])
@endcomponent
@php
    $cellStyle = 'text-align: center; font-size:32px; letter-spacing: 12px; padding-top: 20px; padding-bottom: 20px; background-color:#FAFAFA;width: 60%; max-width: 60%; min-width: 60%;';
@endphp
<table cellpadding="0" cellspacing="0" border="0" width="88%"
    style="width: 88% !important; min-width: 88%; max-width: 88%; border: none; border-spacing: 10px">
    <tbody>
        <tr>
            <td width="20%" style="width: 20%; max-width: 20%; min-width: 20%;">
                &nbsp;</td>
            <td width="60%" style="{{ $cellStyle }}">
                {{ $code }}
            </td>
            <td width="20%" style="width: 20%; max-width: 20%; min-width: 20%;">
                &nbsp;</td>
        </tr>
    </tbody>
</table>
@component('mail::components.space', ['height' => 36])
@endcomponent

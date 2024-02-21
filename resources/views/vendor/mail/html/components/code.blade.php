@component('mail::components.space', ['height' => 36])
@endcomponent
@php
    $cellStyle = 'text-align: center; padding-top: 20px; padding-bottom: 20px; background-color:#FAFAFA;width: 10%; max-width: 10%; min-width: 10%;';
@endphp
<table cellpadding="0" cellspacing="0" border="0" width="88%"
    style="width: 88% !important; min-width: 88%; max-width: 88%; border: none; border-spacing: 10px">
    <tbody>
        <tr>
            <td width="20%" style="width: 20%; max-width: 20%; min-width: 20%;">
                &nbsp;</td>
            <td width="10%" style="{{ $cellStyle }}">
                {{ $code[0] }}
            </td>
            <td width="10%" style="{{ $cellStyle }}">
                {{ $code[1] }}</td>
            <td width="10%" style="{{ $cellStyle }}">
                {{ $code[2] }}</td>
            <td width="10%" style="{{ $cellStyle }}">
                {{ $code[3] }}
            </td>
            <td width="10%" style="{{ $cellStyle }}">
                {{ $code[4] }}</td>
            <td width="10%" style="{{ $cellStyle }}">
                {{ $code[5] }}</td>
            <td width="20%" style="width: 20%; max-width: 20%; min-width: 20%;">
                &nbsp;</td>
        </tr>
    </tbody>
</table>
@component('mail::components.space', ['height' => 36])
@endcomponent

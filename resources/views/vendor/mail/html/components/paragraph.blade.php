<table cellpadding="0" cellspacing="0" border="0" width="88%"
    style="width: 88% !important; min-width: 88%; max-width: 88%;">
    <tr>
        <td align="{{ $align ?? 'left' }}" class="paragraph-wrapper" valign="top">
            @isset($title)
                <div class="title">{{ $title }}</div>
            @endisset
            <div class="content">
                {{ $slot }}
            </div>
            @component('mail::components.space', ['height' => 24])
            @endcomponent
        </td>
    </tr>
</table>

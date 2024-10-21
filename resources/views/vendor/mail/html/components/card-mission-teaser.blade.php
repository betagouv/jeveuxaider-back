@component('mail::components.space', ['height' => 12])
@endcomponent
<table class="mail-table-card-teaser-mission" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>
                        <div style="font-size: 14px; font-weight: 700; text-transform: uppercase; margin-bottom: 2px;">
                            @component('mail::components.mission.domaine', ['mission' => $mission])
                            @endcomponent
                        </div>
                        <div style=" margin-bottom: 2px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                            <a href="{{ $url }}" style="font-size: 20px; font-weight: 700; color: #161616; text-decoration: none;">{{ $mission->name }}</a>
                        </div>
                        <div style="font-size: 16px; font-weight: 400; color: #666666; margin-bottom: 2px;">
                            @if($mission->type == 'Mission à distance')
                                À distance
                            @else
                                @component('mail::components.mission.addresses-cities', ['mission' => $mission])
                                @endcomponent
                            @endif
                            • {{ config('taxonomies.commitment.terms')[$mission->commitment] }}
                        </div>
                        <div style="font-size: 16px; font-weight: 400; color: #000091; text-decoration: underline;">
                            <a href="{{ $url }}">Ouvrir la mission</a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@component('mail::components.space', ['height' => 12])
@endcomponent
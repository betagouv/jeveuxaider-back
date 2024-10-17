@component('mail::components.space', ['height' => 12])
@endcomponent
<table class="mail-table-card-teaser-mission" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <!-- <td style="padding-right: 20px;">
                        @component('mail::components.mission.picture', ['mission' => $mission, 'conversionName' => 'cardMail', 'width' => 215, 'height' => 150, 'url' => $url])
                        @endcomponent
                    </td> -->
                    <td>
                        <!-- <div style="font-size: 22px; font-weight: 700; color: #161616; margin-bottom: 16px;">
                            {{ $mission->id }}
                        </div> -->
                        <div style="font-size: 14px; font-weight: 700; text-transform: uppercase; margin-bottom: 2px;">
                            @component('mail::components.mission.domaine', ['mission' => $mission])
                            @endcomponent
                        </div>
                        <div style="font-size: 20px; font-weight: 700; color: #161616; margin-bottom: 2px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                            {{ $mission->name }}
                        </div>
                        @if($mission->addresses)
                            <div style="font-size: 16px; font-weight: 400; color: #666666; margin-bottom: 2px;">
                                @component('mail::components.mission.addresses-cities', ['mission' => $mission])
                                @endcomponent
                            </div>
                        @elseif($mission->commitment)
                            <div style="font-size: 16px; font-weight: 400; color: #666666; margin-bottom: 2px;">
                                {{ config('taxonomies.commitment.terms')[$mission->commitment] }}
                            </div>
                        @endif
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
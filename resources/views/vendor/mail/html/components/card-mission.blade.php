@component('mail::components.space', ['height' => 24])
@endcomponent
<table class="mail-table-card-mission" width="88%" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            @if ($mission->picture)
                <a href="{{ $url }}">
                    <img srcset="{{ $mission->picture['large'] }}" alt="" style="max-width: 100%; height: auto;" />
                </a>
            @endif
        </td>
    </tr>
    <tr>
        <td style="padding: 20px;">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>
                        <div style="font-size: 22px; font-weight: 700; color: #161616; margin-bottom: 16px;">
                            {{ $mission->name }}
                        </div>
                        <div style="font-size: 14px; margin-bottom: 4px;">
                            <img style="display: inline;" src="{{ config('app.url') }}/images/icones/marker.svg" alt="" width="16" />
                            @if ($mission->type == 'Mission en présentiel')
                                {{ $mission->type }}
                            @endif
                            @if ($mission->type == 'Mission à distance')
                                Mission à distance
                            @endif
                        </div>
                        @if ($mission->commitment__duration)
                            <div style="font-size: 14px; margin-bottom: 16px;">
                                <img style="display: inline;" src="{{ config('app.url') }}/images/icones/clock.svg" alt="" width="16" />
                                @if ($mission->commitment__time_period)
                                    {{ Utils::labelFromValue($mission->commitment__duration, 'duration') }} par {{ Utils::labelFromValue($mission->commitment__time_period, 'time_period') }}
                                @else
                                    {{ Utils::labelFromValue($mission->commitment__duration, 'duration') }}
                                @endif
                            </div>
                        @endif
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td>
                                    <div style="font-size: 14px;">
                                        Proposée par <strong>{{ $mission->structure->name }}</strong>
                                    </div>
                                </td>
                                <td align="right">
                                    <a href="{{ $url }}">
                                        <img src="{{ config('app.url') }}/images/icones/right-arrow-line.svg" alt="" width="28" />
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@component('mail::components.space', ['height' => 24])
@endcomponent

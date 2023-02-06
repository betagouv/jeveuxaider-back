@component('mail::components.space', ['height' => 24])
@endcomponent
<table class="table-model" width="88%" cellpadding="0" cellspacing="0">
    <tr>
        <td style="padding: 10px 0"></td>
    </tr>
    <tr>
        <td style="border-right: 1px solid #DDDDDD; padding: 20px; width: 76px;" valign="top">
            <img style="width: 35px; height: 31px;"
                src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzUiIGhlaWdodD0iMzEiIHZpZXdCb3g9IjAgMCAzNSAzMSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiCmQ9Ik0zMi44NzQ4IDAuMTI1QzMzLjgxODMgMC4xMjUgMzQuNTgzMiAwLjg4OTg0NyAzNC41ODMyIDEuODMzMzNWMjkuMTY2N0MzNC41ODMyIDMwLjExMDIgMzMuODE4MyAzMC44NzUgMzIuODc0OCAzMC44NzVIMi4xMjQ4NEMxLjE4MTM1IDMwLjg3NSAwLjQxNjUwNCAzMC4xMTAyIDAuNDE2NTA0IDI5LjE2NjdWMS44MzMzM0MwLjQxNjUwNCAwLjg4OTg0NyAxLjE4MTM1IDAuMTI1IDIuMTI0ODQgMC4xMjVIMzIuODc0OFpNMzEuMTY2NSAxMy43OTE3SDMuODMzMTdWMjcuNDU4M0gzMS4xNjY1VjEzLjc5MTdaTTEwLjY2NjUgMTUuNVYyNC4wNDE3SDUuNTQxNVYxNS41SDEwLjY2NjVaTTMxLjE2NjUgMy41NDE2N0gzLjgzMzE3VjEwLjM3NUgzMS4xNjY1VjMuNTQxNjdaTTguOTU4MTcgNS4yNVY4LjY2NjY3SDUuNTQxNVY1LjI1SDguOTU4MTdaTTE1Ljc5MTUgNS4yNVY4LjY2NjY3SDEyLjM3NDhWNS4yNUgxNS43OTE1WiIKZmlsbD0iIzAwMDA5MSIgLz4KPC9zdmc+">
        </td>
        <td style="padding: 0 20px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                @component('mail::components.table-divider')
                    La mission
                @endcomponent
                <tr>
                    <td>
                        <div class="title-blue">{{ $mission->name }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="padding-right: 10px; width: 50px; font-size: 28px; line-height: 36px;"
                                    valign="top">📍
                                </td>
                                <td>
                                    @if ($mission->is_autonomy)
                                        <div class="text-label">Mission en autonomie</div>
                                        <div class="text-value">
                                            @foreach ($mission->autonomy_zips as $item)
                                                {{ $item['city'] }} {{ $item['zip'] }} {{ $loop->last ? '' : ' • ' }}
                                            @endforeach
                                    @endif
                                    @if ($mission->type == 'Mission en présentiel' && !$mission->is_autonomy)
                                        <div class="text-label">{{ $mission->city }} {{ $mission->zip }}</div>
                                        <div class="text-value">{{ $mission->address }}</div>
                                    @endif
                                    @if ($mission->type == 'Mission à distance')
                                        <div class="text-label">Mission à distance</div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td style="padding-right: 10px; width: 50px; font-size: 28px; line-height: 36px;"
                                    valign="top">🗓️
                                </td>
                                <td>
                                    <div class="text-label">
                                        @if ($mission->start_date && $mission->end_date)
                                            @if ($mission->start_date == $mission->end_date)
                                                {{ Utils::formatDate($mission->start_date) }}
                                            @else
                                                Entre le {{ Utils::formatDate($mission->start_date) }} et le
                                                {{ Utils::formatDate($mission->end_date) }}
                                            @endif
                                        @else
                                            @if ($mission->date_type == 'recurring')
                                                Mission récurrente à partir du
                                                {{ Utils::formatDate($mission->start_date) }}
                                            @else
                                                {{ Utils::formatDate($mission->start_date) }}
                                            @endif
                                        @endif
                                    </div>
                                    <div class="text-value">
                                        @if ($mission->commitment__time_period)
                                            {{ Utils::labelFromValue($mission->commitment__duration, 'duration') }} par
                                            {{ Utils::labelFromValue($mission->commitment__time_period, 'time_period') }}
                                        @else
                                            {{ Utils::labelFromValue($mission->commitment__duration, 'duration') }}
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @if (count($mission->publics_beneficiaires) > 0)
                    @component('mail::components.table-divider')
                        Publics aidés
                    @endcomponent
                    <tr>
                        <td>
                            @foreach ($mission->publics_beneficiaires as $item)
                                @component('mail::components.tag', ['variant' => 'blue'])
                                    {{ Utils::labelFromValue($item, 'mission_publics_beneficiaires') }}
                                @endcomponent
                            @endforeach
                        </td>
                    </tr>
                @endif
                @isset($showInfos)
                    @component('mail::components.table-divider')
                        Informations
                    @endcomponent
                    <tr>
                        <td>
                            @component('mail::components.tag', ['variant' => 'default'])
                                {{ $mission->state }}
                            @endcomponent
                        </td>
                    </tr>
                @endisset
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 10px 0"></td>
    </tr>
</table>
@component('mail::components.space', ['height' => 24])
@endcomponent

@component('mail::components.space', ['height' => 24])@endcomponent
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
                    {{ $organisation->statut_juridique }}
                @endcomponent
                <tr>
                    <td>
                        <div class="title-blue">{{ $organisation->name }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding-right: 10px; width: 50px; font-size: 28px; line-height: 36px;"
                                    valign="top">📍</td>
                                <td>
                                    @if ($organisation->city && $organisation->zip)
                                        <div class="text-label">{{ $organisation->city }} {{ $organisation->zip }}</div>
                                        <div class="text-value">{{ $organisation->address }}</div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @if (count($organisation->publics_beneficiaires) > 0)
                    @component('mail::components.table-divider')
                        Publics aidés
                    @endcomponent
                    <tr>
                        <td>
                            @foreach ($organisation->publics_beneficiaires as $item)
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
                                {{ $organisation->state }}
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
@component('mail::components.space', ['height' => 24])@endcomponent

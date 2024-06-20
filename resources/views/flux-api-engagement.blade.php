<?xml version="1.0" encoding="UTF-8" ?>
<source>
<publisher>Réserve Civique</publisher>
<publisherurl>https://jeveuxaider.gouv.fr</publisherurl>
<lastBuildDate>{{ now() }}</lastBuildDate>
@foreach ($missions as $mission)
    <mission>
        @php
            $firstAddress = $mission->addresses ? $mission->addresses[0] : null;
        @endphp
        <title>
            <![CDATA[{{ $mission->name }}]]>
        </title>
        <clientId>
            <![CDATA[{{ $mission->id }}]]>
        </clientId>
        <description>
            <![CDATA[{{ $mission->description }}\n\nObjectifs: \n{{ $mission->objectif }}]]>
        </description>
        <applicationUrl>
            <![CDATA[{{ config('app.front_url') }}/missions-benevolat/{{ $mission->id }}/{{ $mission->slug }}]]>
        </applicationUrl>
        <organizationId>
            <![CDATA[{{ $mission->structure->id }}]]>
        </organizationId>
        <organizationName>
            <![CDATA[{{ $mission->structure->name }}]]>
        </organizationName>
        <organizationLogo>
            @if ($mission->structure->logo)
                <![CDATA[{{ $mission->structure->logo->getFullUrl() }}]]>
            @endif
        </organizationLogo>
        <organizationStatusJuridique>
            <![CDATA[{{ $mission->structure->statut_juridique }}]]>
        </organizationStatusJuridique>
        <organizationUrl>
            @php
                $assoFrontUrl =
                    $mission->structure->statut_juridique == 'Association'
                        ? config('app.front_url') . '/organisations/' . $mission->structure->slug
                        : null;
            @endphp
            <![CDATA[{{ $assoFrontUrl }}]]>
        </organizationUrl>
        <organizationWebsite>
            <![CDATA[{{ $mission->structure->website }}]]>
        </organizationWebsite>
        <organizationFullAddress>
            <![CDATA[{{ $mission->structure->address }}, {{ $mission->structure->zip }} {{ $mission->structure->city }}, {{ $mission->structure->country }}]]>
        </organizationFullAddress>
        <organizationReseaux>
            @if ($mission->structure->reseaux)
                @foreach ($mission->structure->reseaux as $reseau)
                    <value>
                        <![CDATA[{{ $reseau->name }}]]>
                    </value>
                @endforeach
            @endif
        </organizationReseaux>
        <postedAt>
            <![CDATA[{{ $mission->created_at }}]]>
        </postedAt>
        <startAt>
            <![CDATA[{{ $mission->start_date }}]]>
        </startAt>
        <endAt>
            <![CDATA[{{ $mission->end_date }}]]>
        </endAt>
        <adresse>
            <![CDATA[{{ $firstAddress ? $firstAddress['label'] : null }}]]>
        </adresse>
        <postalCode>
            <![CDATA[{{ $firstAddress ? $firstAddress['zip'] : null }}]]>
        </postalCode>
        <departmentName>
            <![CDATA[{{ $firstAddress ? config('taxonomies.departments.terms')[ $firstAddress['department']] : null }}]]>
        </departmentName>
        <departmentCode>
            <![CDATA[{{ $firstAddress ? $firstAddress['department'] : null }}]]>
        </departmentCode>
        <city>
            <![CDATA[{{ $firstAddress ? $firstAddress['city'] : null }}]]>
        </city>
        <country>
            <![CDATA[{{ 'France' }}]]>
        </country>
        <lonlat>
            <![CDATA[{{ $firstAddress && isset($firstAddress['longitude']) && isset($firstAddress['latitude']) ? $firstAddress['longitude'] : null }},{{ $firstAddress ? $firstAddress['latitude'] : null }}]]>
        </lonlat>
        <places>
            <![CDATA[{{ $mission->places_left }}]]>
        </places>
        @if ($mission->type == 'Mission en présentiel')
            <remote>
                <![CDATA[no]]>
            </remote>
        @elseif ($mission->type == 'Mission à distance')
            <remote>
                <![CDATA[full]]>
            </remote>
        @endif
        <addressPrecisions>
            <![CDATA[{{ $mission->addresses_precisions }}]]>
        </addressPrecisions>
        <domain>
            @php
                $domainId = $mission->template ? $mission->template->domaine_id : $mission->domaine_id;
                $domainApiName =
                    isset($domainId) && isset(config('taxonomies.api_engagement_domaines.terms')[$domainId])
                        ? config('taxonomies.api_engagement_domaines.terms')[$domainId]
                        : 'autre';
            @endphp
            <![CDATA[{{ $domainApiName }}]]>
        </domain>

        <activity>
            @php
                $activityId = $mission->template ? $mission->template->activity_id : $mission->activity_id;
                $activityApiName =
                    isset($activityId) && isset(config('taxonomies.api_engagement_activities.terms')[$activityId])
                        ? config('taxonomies.api_engagement_activities.terms')[$activityId]
                        : null;
            @endphp
            <![CDATA[{{ $activityApiName }}]]>
        </activity>

        <audience>
            @if ($mission->publics_beneficiaires)
                @foreach ($mission->publics_beneficiaires as $public_beneficiaire)
                    <value>
                        <![CDATA[{{ $public_beneficiaire }}]]>
                    </value>
                @endforeach
            @endif
        </audience>

        <openToMinors>
            @php
                $isOpenToMinors =
                    !empty($mission->publics_volontaires) && in_array('Mineurs', $mission->publics_volontaires);
            @endphp
            <![CDATA[{{ $isOpenToMinors ? 'yes' : 'no' }}]]>
        </openToMinors>

        <snu>
            <![CDATA[{{ $mission->is_snu_mig_compatible ? 'yes' : 'no' }}]]>
        </snu>
        <snuPlaces>
            <![CDATA[{{ $mission->snu_mig_places }}]]>
        </snuPlaces>
        <image>
            @if ($mission->template && $mission->template->photo)
                <![CDATA[{{ $mission->template->photo->urls['original'] }}]]>
            @elseif ($mission->illustrations && isset($mission->illustrations[0]))
                <![CDATA[{{ $mission->illustrations[0]->urls['original'] }}]]>
            @endif
        </image>
        <schedule>
            @php
                $schedule = isset($mission->commitment__duration)
                    ? (isset($mission->commitment__time_period)
                        ? config('taxonomies.duration.terms')[$mission->commitment__duration] .
                            ' par ' .
                            config('taxonomies.time_period.terms')[$mission->commitment__time_period]
                        : config('taxonomies.duration.terms')[$mission->commitment__duration])
                    : null;
            @endphp
            <![CDATA[{{ $schedule }}]]>
        </schedule>
        <tags>
            @if ($mission->tags)
                @foreach ($mission->tags as $tag)
                    <value>
                        <![CDATA[{{ $tag->name }}]]>
                    </value>
                @endforeach
            @endif
        </tags>
    </mission>
@endforeach
</source>

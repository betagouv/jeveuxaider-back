<?xml version="1.0" encoding="UTF-8"?>
<source>
   <publisher>Réserve Civique</publisher>
   <publisherurl>https://covid19.reserve-civique.gouv.fr</publisherurl>
   <lastBuildDate>{{  now() }}</lastBuildDate>
    @foreach ($missions as $mission)
    <mission>
        <title><![CDATA[{{ $mission->name }}]]></title>
        <clientId><![CDATA[{{ $mission->id }}]]></clientId>
        <description><![CDATA[{{ $mission->description }}]]></description>
        <applicationUrl><![CDATA[{{ config('app.url') }}/missions/{{ $mission->id }}]]></applicationUrl>
        <organizationName><![CDATA[{{ $mission->structure->name }}]]></organizationName>
        <organizationId><![CDATA[{{ $mission->structure->id }}]]></organizationId>
        <organizationUrl><![CDATA[{{ $mission->structure->website }}]]></organizationUrl>
        <organizationFullAddress><![CDATA[{{ $mission->structure->address }}, {{ $mission->structure->zip }} {{ $mission->structure->city }}, {{ $mission->structure->country }}]]></organizationFullAddress>
        <postedAt><![CDATA[{{ $mission->created_at }}]]></postedAt>
        <startAt><![CDATA[{{ $mission->start_date }}]]></startAt>
        <endAt><![CDATA[{{ $mission->end_date }}]]></endAt>
        <adresse><![CDATA[{{ $mission->address }}, {{ $mission->zip }} {{ $mission->city }}]]></adresse>
        <postalCode><![CDATA[{{ $mission->zip }}]]></postalCode>
        <departmentName><![CDATA[{{ config('taxonomies.departments.terms')[$mission->department] }}]]></departmentName>
        <departmentCode><![CDATA[{{ $mission->department }}]]></departmentCode>
        <city><![CDATA[{{ $mission->city }}]]></city>
        <country><![CDATA[{{ $mission->country }}]]></country>
        <lonlat><![CDATA[{{ $mission->longitude }},{{ $mission->latitude }}]]></lonlat>
        <places><![CDATA[{{ $mission->places_left }}]]></places>
        @if ($mission->type == 'Mission en présentiel')
            <remote><![CDATA[no]]></remote>
        @elseif ($mission->type == 'Mission à distance')
            <remote><![CDATA[full]]></remote>
        @endif
        <activity><![CDATA[logistique]]></activity>
        <domain><![CDATA[environnement]]></domain>
    </mission>
    @endforeach
</source>


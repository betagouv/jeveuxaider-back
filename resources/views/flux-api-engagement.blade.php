<?xml version="1.0" encoding="UTF-8" ?>
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
        @php
            $domain_id = $mission->template ? $mission->template->domaine->id : $mission->domaine->id;
        @endphp
        @switch($domain_id)
        @case(1) {{-- Mobilisation covid-19 --}}
            <activity><![CDATA[sante]]></activity>
            @break
        @case(2) {{-- Éducation pour tous  --}}
            <activity><![CDATA[education]]></activity>
            @break
        @case(3) {{-- Santé pour tous  --}}
            <activity><![CDATA[sante]]></activity>
            @break
        @case(4) {{-- Protection de la nature  --}}
            <activity><![CDATA[environnement]]></activity>
            @break
        @case(6) {{-- Solidarité et insertion  --}}
            <activity><![CDATA[solidarite-insertion]]></activity>
            @break
        @case(7) {{-- Sport pour tous  --}}
            <activity><![CDATA[sport]]></activity>
            @break
        @case(8) {{-- Prévention et protection  --}}
            <activity><![CDATA[autre]]></activity>
            @break
        @case(9) {{-- Mémoire et citoyenneté  --}}
            <activity><![CDATA[vivre-ensemble]]></activity>
            @break
        @case(10) {{-- Coopération internationale  --}}
            <activity><![CDATA[vivre-ensemble]]></activity>
            @break
        @case(11) {{-- Art et culture pour tous  --}}
            <activity><![CDATA[culture-loisirs]]></activity>
            @break
        @endswitch
    </mission>
    @endforeach
</source>


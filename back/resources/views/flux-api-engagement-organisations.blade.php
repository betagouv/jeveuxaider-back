<?xml version="1.0" encoding="UTF-8" ?>
<source>
<publisher>Réserve Civique</publisher>
<publisherurl>https://jeveuxaider.gouv.fr</publisherurl>
<lastBuildDate>{{ now() }}</lastBuildDate>

@php
$departments = config('taxonomies.departments.terms');
@endphp

@foreach ($organisations as $organisation)
    <organisation>
        <title>
            <![CDATA[{{ $organisation->name }}]]>
        </title>
        <reseauName>
            <![CDATA[{{ $organisation->reseau ? $organisation->reseau->name : null }}]]>
        </reseauName>
        <clientId>
            <![CDATA[{{ $organisation->id }}]]>
        </clientId>
        <rna>
            <![CDATA[{{ $organisation->rna }}]]>
        </rna>
        <apiId>
            <![CDATA[{{ $organisation->api_id }}]]>
        </apiId>
        <description>
            <![CDATA[{{ $organisation->description }}]]>
        </description>
        <applicationUrl>
            <![CDATA[{{ $organisation->statut_juridique == 'Association' ? config('app.url') . '/organisations/' . $organisation->slug : null }}]]>
        </applicationUrl>
        <statusJuridique>
            <![CDATA[{{ $organisation->statut_juridique }}]]>
        </statusJuridique>
        <domaines>
            @if ($organisation->domaines)
                @foreach ($organisation->domaines as $domaine)
                    <value>
                        <![CDATA[{{ $domaine->name }}]]>
                    </value>
                @endforeach
            @endif
        </domaines>
        <publicsBeneficiaires>
            @if ($organisation->publics_beneficiaires && is_array($organisation->publics_beneficiaires))
                @foreach ($organisation->publics_beneficiaires as $public_beneficiaire)
                    <value>
                        <![CDATA[{{ $public_beneficiaire }}]]>
                    </value>
                @endforeach
            @endif
        </publicsBeneficiaires>
        <website>
            <![CDATA[{{ $organisation->website }}]]>
        </website>
        <facebook>
            <![CDATA[{{ $organisation->facebook }}]]>
        </facebook>
        <twitter>
            <![CDATA[{{ $organisation->twitter }}]]>
        </twitter>
        <instagram>
            <![CDATA[{{ $organisation->instagram }}]]>
        </instagram>
        <donation>
            <![CDATA[{{ $organisation->donation }}]]>
        </donation>
        <fullAddress>
            <![CDATA[{{ $organisation->address }}, {{ $organisation->zip }} {{ $organisation->city }}, {{ $organisation->country }}]]>
        </fullAddress>
        <adresse>
            <![CDATA[{{ $organisation->address }}, {{ $organisation->zip }} {{ $organisation->city }}]]>
        </adresse>
        <postalCode>
            <![CDATA[{{ $organisation->zip }}]]>
        </postalCode>
        <departmentName>
            <![CDATA[{{ $organisation->department && isset($departments[$organisation->department]) ? $departments[$organisation->department] : null }}]]>
        </departmentName>
        <departmentCode>
            <![CDATA[{{ $organisation->department }}]]>
        </departmentCode>
        <city>
            <![CDATA[{{ $organisation->city }}]]>
        </city>
        <country>
            <![CDATA[{{ $organisation->country }}]]>
        </country>
        <lonlat>
            <![CDATA[{{ $organisation->longitude }},{{ $organisation->latitude }}]]>
        </lonlat>
        <postedAt>
            <![CDATA[{{ $organisation->created_at }}]]>
        </postedAt>
    </organisation>
@endforeach
</source>

@component('mail::message')
Hello Giulietta,

<p>Nous avons perdus les référents des départments ci-dessous :</p>

@foreach ($items as $key => $department)

# {{ $key }} - {{ $department['department_name'] }}
@if (count($department['missions']) === 1)
* {{ count($department['missions']) }} mission en attente
@elseif (count($department['missions']) > 1)
* {{ count($department['missions']) }} missions en attentes
@endif
@if (count($department['structures']) === 1)
* {{ count($department['structures']) }} organisation en attente
@elseif (count($department['structures']) > 1)
* {{ count($department['structures']) }} organisations en attentes
@endif

Référents :
<br />
@foreach ($department['referents'] as $referent)
* {{ $referent['first_name'] }} {{ $referent['last_name'] }} : {{ $referent['email'] }} | {{ $referent['mobile'] }}<br />
@endforeach
<br />
@endforeach

Belle journée,

Le code de JeVeuxAider.gouv.fr
@endcomponent

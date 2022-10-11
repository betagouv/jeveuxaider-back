@component('mail::message')
Bonjour,

{{ $note->user->profile->full_name}} a posté une nouvelle note sur « {{ $note->notable->name }} »

@component('mail::quote')
{{ $note->content }}
@endcomponent

@if ($note->notable_type == 'App\Models\Structure')
@component('mail::organisation', ['organisation' => $notable])
Test slot
@endcomponent
@endif

@if ($note->notable_type == 'App\Models\Mission')
@component('mail::mission', ['mission' => $notable])
Test slot
@endcomponent
@endif


@component('mail::button', ['url' => $url])
Accéder à la fiche
@endcomponent

Belle journée,

L'équipe de JeVeuxAider.gouv.fr
@endcomponent

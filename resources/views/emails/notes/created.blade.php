@component('mail::message')
Bonjour,

{{ $note->user->profile->full_name}} a posté une nouvelle note sur « {{ $note->notable->name }} »

@component('mail::panel')
{{ $note->content }}
@endcomponent

@component('mail::button', ['url' => $url])
Accéder à la fiche
@endcomponent

Belle journée,

Le code de JeVeuxAider.gouv.fr
@endcomponent

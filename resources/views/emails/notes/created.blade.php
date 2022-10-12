@component('mail::message')
Bonjour {{ $notifiable->first_name}},

{{ $note->user->profile->full_name}} a posté une nouvelle note sur « {{ $note->notable->name }} »

@component('mail::components.quote')
{{ $note->content }}
@endcomponent

@if ($note->notable_type == 'App\Models\Structure')
@component('mail::components.table-organisation', ['organisation' => $notable, 'showInfos' => true])@endcomponent
@endif

@if ($note->notable_type == 'App\Models\Mission')
@component('mail::components.table-mission', ['mission' => $notable, 'showInfos' => true])@endcomponent
@endif


@component('mail::button', ['url' => $url])
Accéder à la fiche
@endcomponent

Belle journée,

L'équipe de JeVeuxAider.gouv.fr
@endcomponent

@component('mail::message')
Bonjour,

{{ $user->profile->full_name}} a demandé à désinscrire l'organisation « {{ $structure->name }} ». Cependant, des particpations sont reliées à celle-ci.

@component('mail::components.table-organisation', ['organisation' => $structure, 'showInfos' => true])@endcomponent

@component('mail::button', ['url' => $url])
Accéder à la fiche
@endcomponent

Belle journée,

L'équipe de JeVeuxAider.gouv.fr
@endcomponent

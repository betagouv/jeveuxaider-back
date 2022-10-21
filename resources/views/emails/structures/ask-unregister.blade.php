@component('mail::message')
Bonjour,

{{ $user->profile->full_name}} a demandé à désinscrire l'organisation « {{ $structure->name }} »

@component('mail::components.table-organisation', ['organisation' => $structure, 'showInfos' => true])@endcomponent

@component('mail::button', ['url' => $url])
Accéder à la fiche
@endcomponent

Belle journée,

L'équipe de JeVeuxAider.gouv.fr
@endcomponent

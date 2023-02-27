@component('mail::message')
    @component('mail::components.headline')
        Bonjour,
    @endcomponent
    @component('mail::components.paragraph')
        {{ $user->profile->full_name }} a demandé à désinscrire l'organisation « {{ $structure->name }} ». Cependant, des
        participations sont reliées à celle-ci.
    @endcomponent
    @component('mail::components.table-organisation', ['organisation' => $structure, 'showInfos' => true])
    @endcomponent
    @component('mail::button', ['url' => $url])
        Accéder à la fiche
    @endcomponent
@endcomponent

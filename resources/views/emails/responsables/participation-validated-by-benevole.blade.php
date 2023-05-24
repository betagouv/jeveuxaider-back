@component('mail::message')
    @component('mail::components.headline')
        {{ $benevole->full_name }} vient de valider sa participation 👏🏻
    @endcomponent
    @component('mail::components.paragraph')
        Le bénévole a déclaré avoir effectué la mission <strong style="color: #1a1a1a; font-weight: 600;">{{ $mission->name }}</strong>. Le statut de la participation a donc été validé.
    @endcomponent
    @component('mail::components.paragraph')
        Si le bénévole n’a pas participé à la mission, vous pouvez modifier le statut directement depuis votre espace. Vous avez le dernier mot !
    @endcomponent
    @component('mail::components.space', ['height' => 24])
    @endcomponent
    @component('mail::button', ['url' => $url])
        Voir la participation
    @endcomponent
    @component('mail::components.space', ['height' => 48])
    @endcomponent
    @component('mail::components.tips', ['title' => 'Une question ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent
@endcomponent

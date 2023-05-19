@component('mail::message')
    @component('mail::components.headline')
        {{ $benevole->first_name }}, avez-vous réalisé votre mission chez {{ $organisation->name }} ? 🙌
    @endcomponent
    @component('mail::components.paragraph')
        Nous vous invitons à mettre dès à présent à jour le statut de votre participation ! 😉
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Petit rappel de la mission'])
        {{ $mission->name }}
    @endcomponent
    @component('mail::components.paragraph')
        <p>Suivez le lien ci-dessous pour <strong style="color: #1a1a1a; font-weight: 600;">confirmer votre participation</strong> à la mission. Vous pouvez également l'annuler le cas échéant.</p>
        @component('mail::components.space', ['height' => 24])
        @endcomponent
        @component('mail::button', ['url' => $url])
            Mettre à jour ma participation
        @endcomponent
    @endcomponent
@endcomponent

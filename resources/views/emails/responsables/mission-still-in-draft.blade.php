@component('mail::message')
    @component('mail::components.headline')
        Votre mission attend d’être mise en ligne 🗒️
    @endcomponent
    @component('mail::components.paragraph')
        Cette mission n’a pas encore été validée. Les visiteurs ne peuvent pas la consulter pour le moment. C'est dommage !
    @endcomponent
    @component('mail::components.paragraph')
        Pour la mettre en ligne, il suffit de modifier la mission concernée puis de cliquer sur le bouton « Soumettre à
        validation » en bas de page.
    @endcomponent
    @component('mail::button', ['url' => $url])
        Modifier la mission
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent
@endcomponent

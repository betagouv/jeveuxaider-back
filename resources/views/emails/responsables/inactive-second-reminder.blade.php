@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->profile->first_name }} 👋🏻,
    @endcomponent
    @component('mail::components.paragraph')
        <p>Vous avez du mal à mobiliser des bénévoles sur des besoins ponctuels ?</p>
        <p>Votre mission approche à grands pas ?</p>
        <p>Pas de panique. Chez <a href="{{ $urlHome }}">JeVeuxAider.gouv.fr</a>, la plateforme publique du bénévolat, nous avons une fonctionnalité <a href="{{ $urlAgenda }}">agenda</a> qui permet de trouver une mission de bénévolat en fonction d’une date spécifique.</p>
        <p>C’est l'idéal pour mobiliser vos futurs bénévoles sur des missions qui vont avoir lieu dans les prochains jours. Les missions avec créneaux apparaissent en premier. Il suffit de penser à utiliser l’option lors de la création de votre mission ! 🤪</p>
    @endcomponent
    @component('mail::button', ['url' => $urlAddMission])
        Créer une mission de bénévolat
    @endcomponent
    @component('mail::components.space', ['height' => 48])
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent
@endcomponent

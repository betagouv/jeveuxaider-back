@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->profile->first_name }} 👋🏻,
    @endcomponent
    @component('mail::components.paragraph')
        Cela fait déjà trois mois qu’on ne s’est pas vu sur <a href="{{ $urlHome }}">JeVeuxAider.gouv.fr</a> la plateforme publique du bénévolat. 😭
    @endcomponent
    @component('mail::components.paragraph')
        <p>Les jours filent beaucoup trop vite. C’est pourquoi <a href="{{ $urlHome }}">JeVeuxAider.gouv.fr</a> est là pour vous faire <strong style="color: #1a1a1a; font-weight: 600;">gagner un temps précieux</strong>. En vous aidant à mobiliser vos bénévoles sur vos projets. Mais pour ça c’est mieux d’avoir une petite présentation de la plateforme et de ses fonctionnalités !</p>
        <p>Créneaux, modèles de missions, campagnes… On vous présente tout ça ?</p>
    @endcomponent
    @component('mail::button', ['url' => "https://app.livestorm.co/jeveuxaider"])
        M’inscrire au prochain webinaire
    @endcomponent
    @component('mail::components.space', ['height' => 48])
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent
@endcomponent

@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->profile->first_name }} 👋🏻,
    @endcomponent
    @component('mail::components.paragraph')
        Cela fait déjà trois mois qu’on ne s’est pas vu sur <a class="link" href="{{ $urlHome }}">JeVeuxAider.gouv.fr</a> la plateforme publique du bénévolat. 😭
    @endcomponent
    @component('mail::components.paragraph')
        Le temps file beaucoup trop vite. Du coup, on vous propose quelque chose qui permet d’appuyer sur pause : <strong style="color: #1a1a1a; font-weight: 600;">l’agenda</strong>. Mais façon bénévolat !
    @endcomponent
    @component('mail::components.space', ['height' => 16])
    @endcomponent
    @component('mail::button', ['url' => $urlAgenda])
        Découvrir l’agenda du bénévolat
    @endcomponent
    @component('mail::components.space', ['height' => 48])
    @endcomponent
    @component('mail::components.paragraph')
        Il vous permet de trouver une mission dans les prochains jours à côté de chez vous. Et pas n’importe laquelle, celle qui vous touche au cœur, pile poil au moment qui vous arrange. Choisissez le jour, l’heure, votre action <strong style="color: #1a1a1a; font-weight: 600;">et hop dans l’agenda !</strong> On essaye ?
    @endcomponent
    @component('mail::components.tips', ['title' => 'Vous avez des questions ?'])
        N’hésitez pas à écrire au support en retour de ce mail.
    @endcomponent
@endcomponent

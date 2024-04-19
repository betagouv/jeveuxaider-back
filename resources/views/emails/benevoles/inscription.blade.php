@component('mail::message')
    @component('mail::components.headline')
        Bienvenue sur JeVeuxAider.gouv.fr 💙
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Des milliers d’organisations ont besoin de vous.'])
        Ca fait du monde, et beaucoup de manières de s’engager.<br>
        Et si on vous aidait à découvrir LA mission de bénévolat qui vous ressemble, en seulement 4 questions ?
    @endcomponent
    @component('mail::components.space', ['height' => 32])
    @endcomponent
    @component('mail::button', ['url' => $urlQuiz])
        Répondre à notre quiz
    @endcomponent
    @component('mail::components.space', ['height' => 32])
    @endcomponent
    @component('mail::components.quiz', ['url' => $urlQuiz])
    @endcomponent
    @component('mail::components.tips', ['title' => 'Comment trouver une mission ?'])
        Rien de plus simple, il suffit de vous rendre sur <a class="link" href="{{$urlHome}}">JeVeuxAider.gouv.fr</a> et de renseigner votre ville ou département. Vous pouvez ensuite trier selon vos centres d’intérêt et lancer la recherche pour faire votre choix parmi de nombreuses missions de bénévolat. Si vous avez un doute, <a class="link" href="https://www.youtube.com/watch?v=R-gEYk-06I4&ab_channel=JeVeuxAider-gouv-fr">regardez notre vidéo ›</a>
    @endcomponent
    @component('mail::components.tips', ['title' => 'Quelques consignes pour bien débuter !'])
        <p>Afin d’assurer une bonne utilisation de la plateforme par tous, nous vous invitons à prendre connaissance de notre charte de bon fonctionnement.</p>
        <p>👉 <a class="link" href="{{ $urlCharte }}">C'est par ici !</a></p>
    @endcomponent
@endcomponent

@component('mail::message')
    @component('mail::components.headline')
        Bonjour,
    @endcomponent
    @component('mail::components.paragraph')
        Vous avez reçu un nouveau lead "Tête de réseau" :
    @endcomponent
    @component('mail::table')
        | Libellé | Valeur |
        | ------------- |-------------|
        | Organisation | {{ $form['name'] }} |
        | Nombre d'antennes | {{ $form['nb_antennes'] }} |
        | Nombre d'employés | {{ $form['nb_employees'] }} |
        | Nombre de bénévoles | {{ $form['nb_volunteers'] }} |
        | Prénom | {{ $form['first_name'] }} |
        | Nom | {{ $form['last_name'] }} |
        | E-mail | {{ $form['email'] }} |
        | Téléphone | {{ $form['phone'] }} |
    @endcomponent
    @if (isset($form['description']))
        @component('mail::components.card-message', [
            'title' => $form['first_name'] . ' ' . $form['last_name'],
            'subtitle' => $form['email'],
        ])
            {{ $form['description'] }}
        @endcomponent
    @endif
@endcomponent

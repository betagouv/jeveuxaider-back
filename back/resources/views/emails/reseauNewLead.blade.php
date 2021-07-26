@component('mail::message')
Bonjour,

<p>Vous avez reçu un nouveau lead "Tête de réseau" :</p>

@component('mail::table')
| Libellé       | Valeur        |
| ------------- |:-------------:|
| Organisation  | {{ $form['name'] }} |
| Prénom        | {{ $form['first_name'] }} |
| Nom           | {{ $form['last_name'] }} |
| E-mail        | {{ $form['email'] }} |
@endcomponent

Bonne journée,

Le code de JeVeuxAider
@endcomponent

@component('mail::message')
Bonjour,

<p>Vous avez reçu un nouveau lead "Tête de réseau" :</p>

@component('mail::table')
| Libellé | Valeur |
| ------------- |:-------------:|
| Organisation | {{ $form['name'] }} |
| Nombre d'antennes | {{ $form['nb_antennes'] }} |
| Nombre d'employés | {{ $form['nb_employees'] }} |
| Nombre de bénévoles | {{ $form['nb_volunteers'] }} |
| Prénom | {{ $form['first_name'] }} |
| Nom | {{ $form['last_name'] }} |
| E-mail | {{ $form['email'] }} |
| Téléphone | {{ $form['phone'] }} |
@endcomponent

@if ($form['description'])
<i>{{ $form['description'] }}</i>
@endif

Bonne journée,

Le code de JeVeuxAider
@endcomponent

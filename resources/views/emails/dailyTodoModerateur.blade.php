@component('mail::message')
Hello Gabrielle,

@foreach ($items as $department)

      <p>Il y a {{ count($department['missions']) }} missions en attente</p>
      <p>Il y a {{ count($department['structures']) }} organisations en attente</p>
@endforeach

# Paris - 75 : 5 missions en attente, 10 organisations en attentes
Référents : 
  - Jérémy Pinto : pinto.jeremy@gmail.com, 06 71 90 28 72)
  - André Pinto : pinto.andre@gmail.com, 06 71 90 28 72)


# Yvelines - 78 : <a>5 missions en attente</a>, <a>10 organisations en attentes</a>
Référents : 
  - Jérémy Pinto : pinto.jeremy@gmail.com, 06 71 90 28 72)
  - André Pinto : pinto.andre@gmail.com, 06 71 90 28 72)

Pas de news de PRENOM NOM - Departement

EMAIL TELEPHONE

il a :
- X nouvelles organisations en attente de validation
- X nouvelles missions en attente de validation

Belle journée,

Le code de la Réserve Civique	
@endcomponent
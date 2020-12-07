<?php

namespace App\Services;

class FranceConnect
{

  // Demo : https://fournisseur-de-service.dev-franceconnect.fr/
    // Code source : https://github.com/france-connect/service-provider-example

    protected $client_id = 'b33be9786b828b6ce6b8e634cd331d42590a1862d6ad5faf60ca03d01944d3b1';
    protected $secret_key = 'a2a2575ab0c32052e6511e51a013a732bd60a342ad2a32b5cfd142469fdf2d26';

    // URLs de callback (Facultatif : peut être saisi/mis à jour ultérieurement. Saisir une URL par ligne)
    // https://reserve-civique.test/franceconnect/callback
  // URLs de redirection de déconnexion (Facultatif : peut être saisi/mis à jour ultérieurement. Saisir une URL par ligne)
    // https://reserve-civique.test/franceconnect/logout


    // Fournisseur d'identité "Démonstration"
      // https://github.com/france-connect/identity-provider-example/blob/master/database.csv


  // Les FS doivent donc être des clients OpenID Connect (aussi appelés relying parties),
}

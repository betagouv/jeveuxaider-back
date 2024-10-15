<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INTEREST RATINGS
    |--------------------------------------------------------------------------
    |
    */
    // 'interest_ratings' => [
    //     "vocabulary" => "Intérets",
    //     "terms" => [
    //         "Très intéressé" => "Très intéressé",
    //         "Assez intéressé" => "Assez intéressé",
    //         "Intéressé" => "Intéressé",
    //         "Peu intéressé" => "Peu intéressé",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | INTEREST DEFENSE TYPES
    |--------------------------------------------------------------------------
    |
    */
    // 'interet_defense_types' => [
    //     "vocabulary" => "Types",
    //     "terms" => [
    //         "Au sein d'une préparation militaire ?" => "Au sein d'une préparation militaire ?",
    //         "Au sein d'une association de souvenir" => "Au sein d'une association de souvenir",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | INTEREST DEFENSE DOMAINES
    |--------------------------------------------------------------------------
    |
    */
    // 'interet_defense_domaines' => [
    //     "vocabulary" => "Domaines",
    //     "terms" => [
    //         "Armée de terre" => "Armée de terre",
    //         "Armée de l'air" => "Armée de l'air",
    //         "Marine" => "Marine",
    //         "Pas de préférence" => "Pas de préférence"
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | INTEREST DEFENSE DOMAINES
    |--------------------------------------------------------------------------
    |
    */
    // 'interet_securite_domaines' => [
    //     "vocabulary" => "Domaines",
    //     "terms" => [
    //         "En tant que pompier volontaire" => "En tant que pompier volontaire",
    //         "Au sein de la gendarmerie" => "Au sein de la gendarmerie",
    //         "Dans une association de protection civile" => "Dans une association de protection civile",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE WORKFLOW STATES
    |--------------------------------------------------------------------------
    |
    */
    'structure_workflow_states' => [
        'vocabulary' => 'Statut',
        'terms' => [
            'Brouillon' => 'Brouillon',
            'En attente de validation' => 'En attente de validation',
            'En cours de traitement' => 'En cours de traitement',
            // "En attente de correction" => "En attente de correction",
            'Validée' => 'Validée',
            'Signalée' => 'Signalée',
            'Désinscrite' => 'Désinscrite',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | MISSION WORKFLOW STATES
    |--------------------------------------------------------------------------
    |
    */
    'mission_workflow_states' => [
        'vocabulary' => 'Statut',
        'terms' => [
            'Brouillon' => 'Brouillon',
            'En attente de validation' => 'En attente de validation',
            'En cours de traitement' => 'En cours de traitement',
            'Validée' => 'Validée',
            'Terminée' => 'Terminée',
            'Signalée' => 'Signalée',
            'Annulée' => 'Annulée',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | MISSION OPEN HANDICAP
    |--------------------------------------------------------------------------
    |
    */
    // 'mission_handicap' => [
    //     "vocabulary" => "Mission ouverte aux personnes en situation de handicap",
    //     "terms" => [
    //         "Oui" => "Oui",
    //         "Oui mais en adaptant la mission" => "Oui mais en adaptant la mission",
    //         "Non" => "Non"
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE LEGAL STATUS
    |--------------------------------------------------------------------------
    |
    */
    // 'structure_legal_status' => [
    //     "vocabulary" => "Statut Juridique",
    //     "terms" => [
    //         "Association" => "Association",
    //         "Collectivité" => "Collectivité",
    //         "Structure publique" => "Organisation publique",
    //         "Structure privée" => "Organisation privée",
    //         "Autre" => "Autre",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | ASSOCIATIONS TYPES
    |--------------------------------------------------------------------------
    |
    */
    // 'association_types' => [
    //     "vocabulary" => "Types d'association",
    //     "terms" => [
    //         "Agrément jeunesse et éducation populaire" => "Agrément jeunesse et éducation populaire",
    //         "Agrément service civique" => "Agrément service civique",
    //         "Association complémentaire de l'enseignement public" => "Association complémentaire de l'enseignement public",
    //         "Associations d'usagers du système de santé" => "Associations d'usagers du système de santé",
    //         "Association sportive affiliée à une fédération sportive agréée par l'État" => "Association sportive affiliée à une fédération sportive agréée par l'État",
    //         "Agrément des associations de protection de l'environnement" => "Agrément des associations de protection de l'environnement",
    //         "Association agréée de sécurité civile" => "Association agréée de sécurité civile",
    //         "Autre agrément" => "Autre agrément",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE PUBLIQUE TYPES
    |--------------------------------------------------------------------------
    |
    */
    // 'structure_publique_types' => [
    //     "vocabulary" => "Types structure publique",
    //     "terms" => [
    //         "Etablissement scolaire" => "Etablissement scolaire",
    //         "Etablissement public de santé" => "Etablissement public de santé",
    //         "Etablissement public" => "Etablissement public",
    //         "Service de l'Etat" => "Service de l'Etat",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE PUBLIQUE ETAT TYPES
    |--------------------------------------------------------------------------
    |
    */
    // 'structure_publique_etat_types' => [
    //     "vocabulary" => "Types établissement publique",
    //     "terms" => [
    //         "SDIS (Service départemental d'Incendie et de Secours)" => "SDIS (Service départemental d'Incendie et de Secours)",
    //         "Gendarmerie" => "Gendarmerie",
    //         "Police" => "Police",
    //         "Armées" => "Armées",
    //         "Autre service de l'état" => "Autre service de l'état",
    //         "Autre établissement public" => "Autre établissement public",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE PRIVEE TYPES
    |--------------------------------------------------------------------------
    |
    */
    // 'structure_privee_types' => [
    //     "vocabulary" => "Types structure publique",
    //     "terms" => [
    //         "Établissement de santé privé d'intérêt collectif" => "Établissement de santé privé d'intérêt collectif",
    //         "Entreprise agréée ESUS" => "Entreprise agréée ESUS",
    //         "Autre" => "Autre",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | MISSION FORMATS
    |--------------------------------------------------------------------------
    |
    */
    // 'mission_formats' => [
    //     "vocabulary" => "Formats de mission",
    //     "terms" => [
    //         "Mission ponctuelle" => "Mission ponctuelle",
    //         "Mission récurrente" => "Mission récurrente",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | MISSION TYPES
    |--------------------------------------------------------------------------
    |
    */
    // 'mission_types' => [
    //     "vocabulary" => "Types de mission",
    //     "terms" => [
    //         "Mission en présentiel" => "Mission en présentiel",
    //         "Mission à distance" => "Mission à distance",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | MISSION PERIODES
    |--------------------------------------------------------------------------
    |
    */
    // 'mission_periodes' => [
    //     "vocabulary" => "Périodes",
    //     "terms" => [
    //         'Pendant les vacances scolaires' => "Pendant les vacances scolaires",
    //         'En-dehors des vacances scolaires (mercredi après-midi, soirées et/ou weekends)' => "En-dehors des vacances scolaires (mercredi après-midi, soirées et/ou weekends)",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | MISSION PERIODICITES
    |--------------------------------------------------------------------------
    |
    */
    // 'mission_periodicites' => [
    //     "vocabulary" => "Périodicités",
    //     "terms" => [
    //         "1 heure par jour" => "1 heure par jour",
    //         "2 heures par jour" => "2 heures par jour",
    //         "3 heures par jour" => "3 heures par jour",
    //         "4 heures par jour" => "4 heures par jour",
    //         "1 heure par semaine" => "1 heure par semaine",
    //         "2 heures par semaine" => "2 heures par semaine",
    //         "3 heures par semaine" => "3 heures par semaine",
    //         "4 heures par semaine" => "4 heures par semaine",
    //         "1 journée par semaine" => "1 journée par semaine",
    //         "2 journées par semaine" => "2 journées par semaine",
    //         "3 journées par semaine" => "3 journées par semaine",
    //         "4 journées par semaine" => "4 journées par semaine",
    //         "5 journées par semaine" => "5 journées par semaine",
    //         "10 jours par mois" => "10 jours par mois",
    //         "15 jours par mois" => "15 jours par mois",
    //         "20 jours par mois" => "20 jours par mois",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | MISSION DOMAINES
    |--------------------------------------------------------------------------
    |
    */
    // 'mission_domaines' => [
    //     "vocabulary" => "Domaines de mission",
    //     "terms" => [
    //         "Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis" => "Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis, dans la rue ou au sein d’établissements (accueil de jour, centres d’hébergement, hôtels accueillant des personnes en isolement).",
    //         "Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance" => "Je garde des enfants des personnels indispensables à la gestion de la crise sanitaire ou d’une organisation de l’Aide Sociale à l’Enfance",
    //         "Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)" => "Je maintien un lien avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)",
    //         "Je fais les courses de produits essentiels pour mes voisins les plus fragiles." => "Je fais les courses de produits essentiels pour mes voisins les plus fragiles.",
    //         //"soutien_aux_personnes_agees_en_etablissement" => "J’aide les personnels des EHPAD sur des tâches du quotidien et/ou les résidents à maintenir un lien avec leurs proches grâce aux outils numériques.",
    //         "soutien_scolaire_a_distance" => "J’aide les élèves à faire leurs devoirs.",
    //         "fabrication_distribution_equipements" => "Je participe à la confection d’équipements de protection grand public ou à leur distribution.",
    //         "soutien_mobilisation_sanitaire" => "J’aide les personnels des établissements de santé ou participe à une action à but sanitaire.",
    //         "soutien_reprise_missions_service_public" => "Je contribue à la reprise des missions de service public en lien avec la population.",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | MISSION PUBLICS BENEFICIAIRES
    |--------------------------------------------------------------------------
    |
    */
    'mission_publics_beneficiaires' => [
        'vocabulary' => 'Publics bénéficiaires',
        'terms' => [
            'seniors' => 'Personnes âgées',
            'persons_with_disabilities' => 'Personnes en situation de handicap',
            'people_in_difficulty' => 'Personnes en difficulté',
            'parents' => 'Parents',
            'children' => 'Jeunes / enfants',
            'refugees' => 'Nouveaux Arrivants / Réfugiés',
            'people_being_excluded' => 'Personnes en situation d’exclusion',
            'people_sick' => 'Personnes malades',
            'any_public' => 'Tous publics',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | MISSION PUBLICS VOLONTAIRES
    |--------------------------------------------------------------------------
    |
    */
    // 'mission_publics_volontaires' => [
    //     "vocabulary" => "Publics volontaires",
    //     "terms" => [
    //         // "Personnes en situation de handicap" => "Personnes en situation de handicap",
    //         "Mineurs" => "Mineurs",
    //         "Jeunes volontaires du Service National Universel" => "Jeunes volontaires du Service National Universel"
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | Départements
    |--------------------------------------------------------------------------
    |
    */
    'departments' => [
        'vocabulary' => 'Départements',
        'terms' => [
            '01' => 'Ain',
            '02' => 'Aisne',
            '03' => 'Allier',
            '04' => 'Alpes-de-Haute-Provence',
            '05' => 'Hautes-Alpes',
            '06' => 'Alpes-Maritimes',
            '07' => 'Ardèche',
            '08' => 'Ardennes',
            '09' => 'Ariège',
            '10' => 'Aube',
            '11' => 'Aude',
            '12' => 'Aveyron',
            '13' => 'Bouches-du-Rhône',
            '14' => 'Calvados',
            '15' => 'Cantal',
            '16' => 'Charente',
            '17' => 'Charente-Maritime',
            '18' => 'Cher',
            '19' => 'Corrèze',
            '21' => "Côte-d'Or",
            '22' => "Côtes-d'Armor",
            '23' => 'Creuse',
            '24' => 'Dordogne',
            '25' => 'Doubs',
            '26' => 'Drôme',
            '27' => 'Eure',
            '28' => 'Eure-et-Loir',
            '29' => 'Finistère',
            '2A' => 'Corse-du-Sud',
            '2B' => 'Haute-Corse',
            '30' => 'Gard',
            '31' => 'Haute-Garonne',
            '32' => 'Gers',
            '33' => 'Gironde',
            '34' => 'Hérault',
            '35' => 'Ille-et-Vilaine',
            '36' => 'Indre',
            '37' => 'Indre-et-Loire',
            '38' => 'Isère',
            '39' => 'Jura',
            '40' => 'Landes',
            '41' => 'Loir-et-Cher',
            '42' => 'Loire',
            '43' => 'Haute-Loire',
            '44' => 'Loire-Atlantique',
            '45' => 'Loiret',
            '46' => 'Lot',
            '47' => 'Lot-et-Garonne',
            '48' => 'Lozère',
            '49' => 'Maine-et-Loire',
            '50' => 'Manche',
            '51' => 'Marne',
            '52' => 'Haute-Marne',
            '53' => 'Mayenne',
            '54' => 'Meurthe-et-Moselle',
            '55' => 'Meuse',
            '56' => 'Morbihan',
            '57' => 'Moselle',
            '58' => 'Nièvre',
            '59' => 'Nord',
            '60' => 'Oise',
            '61' => 'Orne',
            '62' => 'Pas-de-Calais',
            '63' => 'Puy-de-Dôme',
            '64' => 'Pyrénées-Atlantiques',
            '65' => 'Hautes-Pyrénées',
            '66' => 'Pyrénées-Orientales',
            '67' => 'Bas-Rhin',
            '68' => 'Haut-Rhin',
            '69' => 'Rhône',
            '70' => 'Haute-Saône',
            '71' => 'Saône-et-Loire',
            '72' => 'Sarthe',
            '73' => 'Savoie',
            '74' => 'Haute-Savoie',
            '75' => 'Paris',
            '76' => 'Seine-Maritime',
            '77' => 'Seine-et-Marne',
            '78' => 'Yvelines',
            '79' => 'Deux-Sèvres',
            '80' => 'Somme',
            '81' => 'Tarn',
            '82' => 'Tarn-et-Garonne',
            '83' => 'Var',
            '84' => 'Vaucluse',
            '85' => 'Vendée',
            '86' => 'Vienne',
            '87' => 'Haute-Vienne',
            '88' => 'Vosges',
            '89' => 'Yonne',
            '90' => 'Territoire de Belfort',
            '91' => 'Essonne',
            '92' => 'Hauts-de-Seine',
            '93' => 'Seine-Saint-Denis',
            '94' => 'Val-de-Marne',
            '95' => "Val-d'Oise",
            '971' => 'Guadeloupe',
            '972' => 'Martinique',
            '973' => 'Guyane',
            '974' => 'La Réunion',
            '976' => 'Mayotte',
            '987' => 'Polynésie française',
            '988' => 'Nouvelle-Calédonie',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Département <-> Région
    |--------------------------------------------------------------------------
    |
    */
    'department_region' => [
        'vocabulary' => 'Département - Région',
        'terms' => [
            '01' => 'Auvergne-Rhône-Alpes',
            '02' => 'Hauts-de-France',
            '03' => 'Auvergne-Rhône-Alpes',
            '04' => "Provence-Alpes-Côte d'Azur",
            '05' => "Provence-Alpes-Côte d'Azur",
            '06' => "Provence-Alpes-Côte d'Azur",
            '07' => 'Auvergne-Rhône-Alpes',
            '08' => 'Grand Est',
            '09' => 'Occitanie',
            '10' => 'Grand Est',
            '11' => 'Occitanie',
            '12' => 'Occitanie',
            '13' => "Provence-Alpes-Côte d'Azur",
            '14' => 'Normandie',
            '15' => 'Auvergne-Rhône-Alpes',
            '16' => 'Nouvelle-Aquitaine',
            '17' => 'Nouvelle-Aquitaine',
            '18' => 'Centre-Val de Loire',
            '19' => 'Nouvelle-Aquitaine',
            '21' => 'Bourgogne-Franche-Comté',
            '22' => 'Bretagne',
            '23' => 'Nouvelle-Aquitaine',
            '24' => 'Nouvelle-Aquitaine',
            '25' => 'Bourgogne-Franche-Comté',
            '26' => 'Auvergne-Rhône-Alpes',
            '27' => 'Normandie',
            '28' => 'Centre-Val de Loire',
            '29' => 'Bretagne',
            '2A' => 'Corse',
            '2B' => 'Corse',
            '30' => 'Occitanie',
            '31' => 'Occitanie',
            '32' => 'Occitanie',
            '33' => 'Nouvelle-Aquitaine',
            '34' => 'Occitanie',
            '35' => 'Bretagne',
            '36' => 'Centre-Val de Loire',
            '37' => 'Centre-Val de Loire',
            '38' => 'Auvergne-Rhône-Alpes',
            '39' => 'Bourgogne-Franche-Comté',
            '40' => 'Nouvelle-Aquitaine',
            '41' => 'Centre-Val de Loire',
            '42' => 'Auvergne-Rhône-Alpes',
            '43' => 'Auvergne-Rhône-Alpes',
            '44' => 'Pays de la Loire',
            '45' => 'Centre-Val de Loire',
            '46' => 'Occitanie',
            '47' => 'Nouvelle-Aquitaine',
            '48' => 'Occitanie',
            '49' => 'Pays de la Loire',
            '50' => 'Normandie',
            '51' => 'Grand Est',
            '52' => 'Grand Est',
            '53' => 'Pays de la Loire',
            '54' => 'Grand Est',
            '55' => 'Grand Est',
            '56' => 'Bretagne',
            '57' => 'Grand Est',
            '58' => 'Bourgogne-Franche-Comté',
            '59' => 'Hauts-de-France',
            '60' => 'Hauts-de-France',
            '61' => 'Normandie',
            '62' => 'Hauts-de-France',
            '63' => 'Auvergne-Rhône-Alpes',
            '64' => 'Nouvelle-Aquitaine',
            '65' => 'Occitanie',
            '66' => 'Occitanie',
            '67' => 'Grand Est',
            '68' => 'Grand Est',
            '69' => 'Auvergne-Rhône-Alpes',
            '70' => 'Bourgogne-Franche-Comté',
            '71' => 'Bourgogne-Franche-Comté',
            '72' => 'Pays de la Loire',
            '73' => 'Auvergne-Rhône-Alpes',
            '74' => 'Auvergne-Rhône-Alpes',
            '75' => 'Île-de-France',
            '76' => 'Normandie',
            '77' => 'Île-de-France',
            '78' => 'Île-de-France',
            '79' => 'Nouvelle-Aquitaine',
            '80' => 'Hauts-de-France',
            '81' => 'Occitanie',
            '82' => 'Occitanie',
            '83' => "Provence-Alpes-Côte d'Azur",
            '84' => "Provence-Alpes-Côte d'Azur",
            '85' => 'Pays de la Loire',
            '86' => 'Nouvelle-Aquitaine',
            '87' => 'Nouvelle-Aquitaine',
            '88' => 'Grand Est',
            '89' => 'Bourgogne-Franche-Comté',
            '90' => 'Bourgogne-Franche-Comté',
            '91' => 'Île-de-France',
            '92' => 'Île-de-France',
            '93' => 'Île-de-France',
            '94' => 'Île-de-France',
            '95' => 'Île-de-France',
            '971' => 'Guadeloupe',
            '972' => 'Martinique',
            '973' => 'Guyane',
            '974' => 'La Réunion',
            '976' => 'Mayotte',
            '987' => null,
            '988' => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Régions
    |--------------------------------------------------------------------------
    |
    */
    'regions' => [
        'vocabulary' => 'Régions',
        'terms' => [
            'Auvergne-Rhône-Alpes' => 'Auvergne-Rhône-Alpes',
            'Bourgogne-Franche-Comté' => 'Bourgogne-Franche-Comté',
            'Bretagne' => 'Bretagne',
            'Centre-Val de Loire' => 'Centre-Val de Loire',
            'Corse' => 'Corse',
            'Grand Est' => 'Grand Est',
            'Hauts-de-France' => 'Hauts-de-France',
            'Île-de-France' => 'Île-de-France',
            'Normandie' => 'Normandie',
            'Nouvelle-Aquitaine' => 'Nouvelle-Aquitaine',
            'Occitanie' => 'Occitanie',
            'Pays de la Loire' => 'Pays de la Loire',
            "Provence-Alpes-Côte d'Azur" => "Provence-Alpes-Côte d'Azur",
            'Guadeloupe' => 'Guadeloupe',
            'Martinique' => 'Martinique',
            'Guyane' => 'Guyane',
            'La Réunion' => 'La Réunion',
            'Mayotte' => 'Mayotte',
        ],
        'departments' => [
            'Auvergne-Rhône-Alpes' => ['01', '03', '42', '63', '15', '43', '07', '26', '38', '73', '74', '69'],
            'Bourgogne-Franche-Comté' => ['89', '21', '70', '90', '25', '39', '71', '58'],
            'Bretagne' => ['29', '22', '35', '56'],
            'Centre-Val de Loire' => ['28', '45', '41', '37', '36', '18'],
            'Corse' => ['2A', '2B', '20'],
            'Grand Est' => ['08', '55', '54', '57', '67', '68', '54', '88', '52', '10'],
            'Hauts-de-France' => ['62', '59', '02', '60', '80'],
            'Île-de-France' => ['75', '77', '78', '91', '92', '93', '94', '95'],
            'Normandie' => ['50', '14', '76', '27', '61'],
            'Nouvelle-Aquitaine' => ['79', '86', '17', '16', '87', '23', '33', '24', '19', '40', '47', '64'],
            'Occitanie' => ['46', '12', '48', '30', '82', '81', '34', '32', '31', '11', '65', '09', '66'],
            'Pays de la Loire' => ['53', '72', '44', '49', '85'],
            "Provence-Alpes-Côte d'Azur" => ['05', '04', '84', '13', '06', '83'],
            'Guadeloupe' => ['971'],
            'Martinique' => ['972'],
            'Guyane' => ['973'],
            'La Réunion' => ['974'],
            'Mayotte' => ['976'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | PARTICIPATION WORKFLOW STATES
    |--------------------------------------------------------------------------
    |
    */
    'participation_workflow_states' => [
        'vocabulary' => 'Statut',
        'terms' => [
            'En attente de validation' => 'En attente de validation',
            'En cours de traitement' => 'En cours de traitement',
            'Validée' => 'Validée',
            'Refusée' => 'Refusée',
            'Annulée' => 'Annulée',
        ],
    ],

    'participation_declined_reasons' => [
        'vocabulary' => 'Reasons',
        'terms' => [
            'no_response' => 'Le bénévole ne répond pas.',
            'requirements_not_fulfilled' => 'Le bénévole ne correspond pas aux besoins.',
            'change_mind' => "Le bénévole a changé d'avis.",
            'mission_terminated' => 'La mission est terminée.',
            'other' => 'Autres.',
        ],
    ],

    'participation_canceled_by_benevole_reasons' => [
        'vocabulary' => 'Reasons',
        'terms' => [
            'no_response' => "L'organisation ne répond pas.",
            'requirements_not_fulfilled' => 'La mission ne correspond pas à mes attentes.',
            'not_available' => 'Je ne suis plus disponible.',
            'other' => 'Autres.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | TAGS TYPES
    |--------------------------------------------------------------------------
    |
    */
    // 'tag_types' => [
    //     "vocabulary" => "Type de tag",
    //     "terms" => [
    //         "domaine" => "Domaine d'action",
    //         "competence" => "Compétence",
    //         "other" => "Autre"
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | TAGS GROUPS
    |--------------------------------------------------------------------------
    |
    */
    // 'tag_groups' => [
    //     "vocabulary" => "Groupe de tag",
    //     "terms" => [
    //         "Accompagnement de la personne" => "Accompagnement de la personne",
    //         "Accueil en hôtellerie" => "Accueil en hôtellerie",
    //         "Accueil et promotion touristique" => "Accueil et promotion touristique",
    //         "Achats" => "Achats",
    //         "Action sociale, socio-éducative et socio-culturelle" => "Action sociale, socio-éducative et socio-culturelle",
    //         "Adaptabilité" => "Adaptabilité",
    //         "Affaires et support technique client" => "Affaires et support technique client",
    //         "Aide à la vie quotidienne" => "Aide à la vie quotidienne",
    //         "Alimentaire" => "Alimentaire",
    //         "Animation d'activités de loisirs" => "Animation d'activités de loisirs",
    //         "Animation de spectacles" => "Animation de spectacles",
    //         "Apprendre à apprendre" => "Apprendre à apprendre",
    //         "Artistes - interprètes du spectacle" => "Artistes - interprètes du spectacle",
    //         "Arts plastiques" => "Arts plastiques",
    //         "Assurance" => "Assurance",
    //         "Auto-évaluation" => "Auto-évaluation",
    //         "Banque" => "Banque",
    //         "Bois" => "Bois",
    //         "Céramique" => "Céramique",
    //         "Chimie et pharmacie" => "Chimie et pharmacie",
    //         "Commerce alimentaire et métiers de bouche" => "Commerce alimentaire et métiers de bouche",
    //         "Commerce non alimentaire et de prestations de confort" => "Commerce non alimentaire et de prestations de confort",
    //         "Communication" => "Communication",
    //         "Compétences analytiques" => "Compétences analytiques",
    //         "Comptabilité et gestion" => "Comptabilité et gestion",
    //         "Conception et études" => "Conception et études",
    //         "Conception et mise en oeuvre des politiques publiques" => "Conception et mise en oeuvre des politiques publiques",
    //         "Conception et production de spectacles" => "Conception et production de spectacles",
    //         "Conception, commercialisation et vente de produits touristiques" => "Conception, commercialisation et vente de produits touristiques",
    //         "Conception, recherche, études et développement" => "Conception, recherche, études et développement",
    //         "Conduite et encadrement de chantier - travaux" => "Conduite et encadrement de chantier - travaux",
    //         "Contrôle public" => "Contrôle public",
    //         "Créativité" => "Créativité",
    //         "Cuir et textile" => "Cuir et textile",
    //         "Culture et gestion documentaire" => "Culture et gestion documentaire",
    //         "Décoration" => "Décoration",
    //         "Défense, sécurité publique et secours" => "Défense, sécurité publique et secours",
    //         "Développement territorial et emploi" => "Développement territorial et emploi",
    //         "Direction d'entreprise" => "Direction d'entreprise",
    //         "Direction de magasin de détail" => "Direction de magasin de détail",
    //         "Direction, encadrement et pilotage de fabrication et production industrielles" => "Direction, encadrement et pilotage de fabrication et production industrielles",
    //         "Droit" => "Droit",
    //         "Edition et communication" => "Edition et communication",
    //         "Electronique et électricité" => "Electronique et électricité",
    //         "Encadrement" => "Encadrement",
    //         "Energie" => "Energie",
    //         "Engins agricoles et forestiers" => "Engins agricoles et forestiers",
    //         "Engins de chantier" => "Engins de chantier",
    //         "Entretien technique" => "Entretien technique",
    //         "Equipements de production, équipements collectifs" => "Equipements de production, équipements collectifs",
    //         "Equipements domestiques et informatique" => "Equipements domestiques et informatique",
    //         "Espaces naturels et espaces verts" => "Espaces naturels et espaces verts",
    //         "Etudes et assistance technique" => "Etudes et assistance technique",
    //         "Extraction" => "Extraction",
    //         "Fibres et papier" => "Fibres et papier",
    //         "Finance" => "Finance",
    //         "Force de vente" => "Force de vente",
    //         "Formation initiale et continue" => "Formation initiale et continue",
    //         "Gestion administrative banque et assurances" => "Gestion administrative banque et assurances",
    //         "Gestion de conflit" => "Gestion de conflit",
    //         "Gestion et direction" => "Gestion et direction",
    //         "Grande distribution" => "Grande distribution",
    //         "Hygiène Sécurité Environnement -HSE- industriels" => "Hygiène Sécurité Environnement -HSE- industriels",
    //         "Images et sons" => "Images et sons",
    //         "Immobilier" => "Immobilier",
    //         "Industries graphiques" => "Industries graphiques",
    //         "Instruments de musique" => "Instruments de musique",
    //         "Leadership positif" => "Leadership positif",
    //         "Magasinage, manutention des charges et déménagement" => "Magasinage, manutention des charges et déménagement",
    //         "Matériaux de construction, céramique et verre" => "Matériaux de construction, céramique et verre",
    //         "Mécanique, travail des métaux et outillage" => "Mécanique, travail des métaux et outillage",
    //         "Métal, verre, bijouterie et horlogerie" => "Métal, verre, bijouterie et horlogerie",
    //         "Méthodes et gestion industrielles" => "Méthodes et gestion industrielles",
    //         "Montage de structures" => "Montage de structures",
    //         "Négociation" => "Négociation",
    //         "Nettoyage et propreté industriels" => "Nettoyage et propreté industriels",
    //         "Organisation de la circulation des marchandises" => "Organisation de la circulation des marchandises",
    //         "Organisation et études" => "Organisation et études",
    //         "Papier et carton" => "Papier et carton",
    //         "Personnel d'encadrement de la logistique" => "Personnel d'encadrement de la logistique",
    //         "Personnel d'encadrement du transport routier" => "Personnel d'encadrement du transport routier",
    //         "Personnel d'étage en hôtellerie" => "Personnel d'étage en hôtellerie",
    //         "Personnel de conduite du transport routier" => "Personnel de conduite du transport routier",
    //         "Personnel navigant du transport aérien" => "Personnel navigant du transport aérien",
    //         "Personnel navigant du transport maritime et fluvial" => "Personnel navigant du transport maritime et fluvial",
    //         "Personnel navigant du transport terrestre" => "Personnel navigant du transport terrestre",
    //         "Personnel sédentaire du transport aérien" => "Personnel sédentaire du transport aérien",
    //         "Personnel sédentaire du transport ferroviaire et réseau filo guidé" => "Personnel sédentaire du transport ferroviaire et réseau filo guidé",
    //         "Personnel sédentaire du transport maritime et fluvial" => "Personnel sédentaire du transport maritime et fluvial",
    //         "Plastique, caoutchouc" => "Plastique, caoutchouc",
    //         "Praticiens médicaux" => "Praticiens médicaux",
    //         "Praticiens médico-techniques" => "Praticiens médico-techniques",
    //         "Préparation et conditionnement" => "Préparation et conditionnement",
    //         "Production" => "Production",
    //         "Production culinaire" => "Production culinaire",
    //         "Professionnels médico-techniques" => "Professionnels médico-techniques",
    //         "Propreté et environnement urbain" => "Propreté et environnement urbain",
    //         "Publicité" => "Publicité",
    //         "Qualité et analyses industrielles" => "Qualité et analyses industrielles",
    //         "Recherche" => "Recherche",
    //         "Rééducation et appareillage" => "Rééducation et appareillage",
    //         "Résolution de problèmes" => "Résolution de problèmes",
    //         "Ressources humaines" => "Ressources humaines",
    //         "Second oeuvre" => "Second oeuvre",
    //         "Secrétariat et assistance" => "Secrétariat et assistance",
    //         "Sécurité privée" => "Sécurité privée",
    //         "Service" => "Service",
    //         "Services funéraires" => "Services funéraires",
    //         "Soins aux animaux" => "Soins aux animaux",
    //         "Soins paramédicaux" => "Soins paramédicaux",
    //         "Sport professionnel" => "Sport professionnel",
    //         "Stratégie commerciale, marketing et supervision des ventes" => "Stratégie commerciale, marketing et supervision des ventes",
    //         "Systèmes d'information et de télécommunication" => "Systèmes d'information et de télécommunication",
    //         "Taxidermie" => "Taxidermie",
    //         "Techniciens du spectacle" => "Techniciens du spectacle",
    //         "Tissu et cuirs" => "Tissu et cuirs",
    //         "Traitements thermiques et traitements de surfaces" => "Traitements thermiques et traitements de surfaces",
    //         "Travail en équipe" => "Travail en équipe",
    //         "Travaux d'accès difficile" => "Travaux d'accès difficile",
    //         "Travaux et gros oeuvre" => "Travaux et gros oeuvre",
    //         "Véhicules, engins, aéronefs" => "Véhicules, engins, aéronefs",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | PROFILE DISPOS
    |--------------------------------------------------------------------------
    |
    */
    // 'profile_disponibilities' => [
    //     "vocabulary" => "Disponibilités",
    //     "terms" => [
    //         "flexible" => "Flexible",
    //         "journee" => "En journée",
    //         "soiree" => "En soirée",
    //         "semaine" => "La semaine",
    //         "weekend" => "Le week-end",
    //         "jours_feries" => "Pendant les jours fériés",
    //         "vacances" => "Pendant les vacances",
    //     ]
    // ],

    // 'profile_types' => [
    //     "vocabulary" => "Type de profil",
    //     "terms" => [
    //         "etudiant" => "Étudiant",
    //         "salarie" => "Salarié",
    //         "travailleur_independant" => "Travailleur Indépendant",
    //         "agent_fonction_publique" => "Agent de la Fonction Publique",
    //         "retraite" => "Retraité",
    //         "chomeur" => "Sans activité professionnelle",
    //         "autre" => "Autre",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    |
    */
    // 'roles' => [
    //     "vocabulary" => "Roles",
    //     "terms" => [
    //         "responsable_organisation" => "Responsable d'une organisation",
    //         "responsable_territoire" => "Responsable d'un territoire",
    //         "referent_departemental" => "Référent départemental",
    //         "referent_regional" => "Référent régional",
    //         "tete_de_reseau" => "Tête de réseau national",
    //         "datas_analyst" => "Datas analyste",
    //         "benevole" => "Bénévole",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | INVITATIONS ROLES
    |--------------------------------------------------------------------------
    |
    */
    // 'invitations_roles' => [
    //     "vocabulary" => "Roles",
    //     "terms" => [
    //         "responsable_organisation" => "Responsable d'une organisation",
    //         "responsable_reseau" => "Responsable d'un réseau",
    //         "responsable_antenne" => "Invitation à créer son antenne",
    //         "responsable_territoire" => "Responsable d'un territoire",
    //         "referent_departemental" => "Référent départemental",
    //         "referent_regional" => "Référent régional",
    //         "datas_analyst" => "Datas analyste",
    //         "benevole" => "Bénévole",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | TERRITOIRES
    |--------------------------------------------------------------------------
    |
    */
    // 'territoires_types' => [
    //     "vocabulary" => "Type des territoires",
    //     "terms" => [
    //         "department" => "Département",
    //         "city" => "Ville",
    //     ]
    // ],
    // 'territoires_states' => [
    //     "vocabulary" => "Statut des territoires",
    //     "terms" => [
    //         "waiting" => "En attente de validation",
    //         "validated" => "Validée",
    //         "refused" => "Refusée",
    //     ]
    // ],

    /*
    |--------------------------------------------------------------------------
    | DOCUMENTS TYPES
    |--------------------------------------------------------------------------
    |
    */
    // 'document_types' => [
    //     "vocabulary" => "Document types",
    //     "terms" => [
    //         "file" => "Fichier",
    //         "link" => "Lien",
    //     ]
    // ],

    'commitment' => [
        "vocabulary" => "Engagement",
        "terms" => [
            "few_hours" => "Quelques heures",
            "few_days" => "Quelques jours",
            "few_hours_a_week" => "Quelques heures par semaine",
            "few_days_a_week" => "Quelques jours par semaine",
            "few_hours_a_month" => "Quelques heures par mois",
            "few_days_a_month" => "Quelques jours par mois",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | DURÉE
    |--------------------------------------------------------------------------
    |
    */
    'duration' => [
        "vocabulary" => "Durée",
        "terms" => [
            "1_hour" => "1 heure",
            "2_hours" => "2 heures",
            "half_day" => "Une demi-journée",
            "day" => "1 jour",
            "2_days" => "2 jours",
            "3_days" => "3 jours",
            "4_days" => "4 jours",
            "5_days" => "5 jours",
            "more_5_days" => "Plus de 5 jours",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | FRÉQUENCE D'ENGAGEMENT
    |--------------------------------------------------------------------------
    |
    */
    'time_period' => [
        "vocabulary" => "Période de temps",
        "terms" => [
            "day" => "jour",
            "week" => "semaine",
            "month" => "mois",
            "year" => "an"
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | NOTES
    |--------------------------------------------------------------------------
    |
    */
    // 'grade' => [
    //     "vocabulary" => "Note",
    //     "terms" => [
    //         "1" => "1 étoile",
    //         "2" => "2 étoiles",
    //         "3" => "3 étoiles",
    //         "4" => "4 étoiles",
    //         "5" => "5 étoiles",
    //     ]
    // ],

    'api_engagement_domaines' => [
        "vocabulary" => "Tableau de correspondance des domaines",
        "terms" => [
            1 => "sante", // Santé pour tous
            2 => "prevention-protection", // Prévention et protection
            3 => "culture-loisirs", // Art et culture pour tous
            4 => "sport", // Sport pour tous
            5 => "sante", // Mobilisation covid-19
            6 => "vivre-ensemble", // Coopération internationale
            7 => "solidarite-insertion", // Solidarité et insertion
            8 => "mémoire et citoyenneté", // Mémoire et citoyenneté
            9 => "education", // Éducation pour tous
            10 => "environnement", // Protection de la nature
            11 => "benevolat-competences", // Bénévolat de compétences
        ]
    ],

    'api_engagement_activities' => [
        "vocabulary" => "Tableau de correspondance des activités",
        "terms" => [
            1 => "collecte", // Maraude
            2 => "sante-soins", // Médical
            3 => "taches-administratives", // Aide aux démarches administratives
            4 => "alphabetisation", // Alphabétisation / Apprentissage du français (FLE)
            5 => "jardinage", // Aménagement d'espaces naturels
            6 => "animation", // Animation / Loisirs
            7 => "collecte", // Collecte de produits
            8 => "lutte-contre-isolement", // Lutte contre l'isolement
            10 => "mentorat-parrainage", // Mentorat & Parrainage
            11 => "autre", // Evenementiel
            12 => "ramassage-dechets", // Ramassage de déchets
            14 => "secourisme", // Secourisme et sécurité civile
            15 => "autre", // Services à la personne
            16 => "soins-animaux", // Soins aux animaux
            17 => "soutien-scolaire", // Soutien scolaire et formation
            18 => "autre", // Vie citoyenne
            19 => "sensibilisation", // Actions de sensibilisation
            20 => "encadrement-d-equipes", // Activités sportives
            21 => "accueil-de-public", // Accueil / Information
            22 => "activites-manuelles", // Travaux manuelles
            23 => "gestion-recherche-des-partenariats", // Collecte de fonds
            24 => "communication", // Communication
            26 => "gestion-recherche-des-partenariats", // Recherche de partenariats
            27 => "autre", // Dialogue interculturel
            28 => "distribution", // Distribution
            29 => "aide-psychologique", // Écoute / Aide psychologique
            30 => "comptabilite-finance", // Gestion financière / comptabilité
            31 => "taches-administratives", // Gestion administrative
            32 => "responsabilites-associatives", // Gouvernance
            33 => "logistique", // Logistique
            34 => "autre", // Médiation culturelle
            35 => "documentation-traduction", // Traduction
            36 => "autre", // Accompagnement à la mobilité
            37 => "gestion-de-projets", // Gestion de projets
            38 => "recrutement", // Gestion des ressources humaines
            39 => "informatique", // Informatique
            40 => "autre", // Valorisation du patrimoine
            41 => "juridique", // Droit et conseil juridique
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | RULES
    |--------------------------------------------------------------------------
    |
    */
    'rules' => [
        "vocabulary" => "Règles",
        "terms" => [
            "mission_attach_tag" => "Ajouter un tag à la mission",
            "mission_detach_tag" => "Retirer un tag à la mission",
        ]
    ],

];

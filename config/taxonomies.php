<?php

return [

    /*
    |--------------------------------------------------------------------------
    | INTEREST RATINGS
    |--------------------------------------------------------------------------
    |
    */
    'interest_ratings' => [
        "vocabulary" => "Intérets",
        "terms" => [
            "Très intéressé" => "Très intéressé",
            "Assez intéressé" => "Assez intéressé",
            "Intéressé" => "Intéressé",
            "Peu intéressé" => "Peu intéressé",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | INTEREST DEFENSE TYPES
    |--------------------------------------------------------------------------
    |
    */
    'interet_defense_types' => [
        "vocabulary" => "Types",
        "terms" => [
            "Au sein d'une préparation militaire ?" => "Au sein d'une préparation militaire ?",
            "Au sein d'une association de souvenir" => "Au sein d'une association de souvenir",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | INTEREST DEFENSE DOMAINES
    |--------------------------------------------------------------------------
    |
    */
    'interet_defense_domaines' => [
        "vocabulary" => "Domaines",
        "terms" => [
            "Armée de terre" => "Armée de terre",
            "Armée de l'air" => "Armée de l'air",
            "Marine" => "Marine",
            "Pas de préférence" => "Pas de préférence"
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | INTEREST DEFENSE DOMAINES
    |--------------------------------------------------------------------------
    |
    */
    'interet_securite_domaines' => [
        "vocabulary" => "Domaines",
        "terms" => [
            "En tant que pompier volontaire" => "En tant que pompier volontaire",
            "Au sein de la gendarmerie" => "Au sein de la gendarmerie",
            "Dans une association de protection civile" => "Dans une association de protection civile",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE WORKFLOW STATES
    |--------------------------------------------------------------------------
    |
    */
    'structure_workflow_states' => [
        "vocabulary" => "Statut",
        "terms" => [
            "En attente de validation" => "En attente de validation",
            // "En attente de correction" => "En attente de correction",
            "Validée" => "Validée",
            "Signalée" => "Signalée"
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | MISSION WORKFLOW STATES
    |--------------------------------------------------------------------------
    |
    */
    'mission_workflow_states' => [
        "vocabulary" => "Statut",
        "terms" => [
            "Brouillon" => "Brouillon",
            "En attente de validation" => "En attente de validation",
            //"En attente de correction" => "En attente de correction",
            "Validée" => "Validée",
            "Signalée" => "Signalée",
            "Annulée" => "Annulée",
           // "Archivée" => "Archivée"
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | MISSION OPEN HANDICAP
    |--------------------------------------------------------------------------
    |
    */
    'mission_handicap' => [
        "vocabulary" => "Mission ouverte aux personnes en situation de handicap",
        "terms" => [
            "Oui" => "Oui",
            "Oui mais en adaptant la mission" => "Oui mais en adaptant la mission",
            "Non" => "Non"
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE LEGAL STATUS
    |--------------------------------------------------------------------------
    |
    */
    'structure_legal_status' => [
        "vocabulary" => "Statut Juridique",
        "terms" => [
            "Association" => "Association",
            "Structure publique" => "Structure publique",
            "Structure privée" => "Structure privée",
            "Autre" => "Autre",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | ASSOCIATIONS TYPES
    |--------------------------------------------------------------------------
    |
    */
    'association_types' => [
        "vocabulary" => "Types d'association",
        "terms" => [
            "Agrément jeunesse et éducation populaire" => "Agrément jeunesse et éducation populaire",
            "Agrément service civique" => "Agrément service civique",
            "Association complémentaire de l'enseignement public" => "Association complémentaire de l'enseignement public",
            "Associations d'usagers du système de santé" => "Associations d'usagers du système de santé",
            "Association sportive affiliée à une fédération sportive agréée par l'État" => "Association sportive affiliée à une fédération sportive agréée par l'État",
            "Agrément des associations de protection de l'environnement" => "Agrément des associations de protection de l'environnement",
            "Association agréée de sécurité civile" => "Association agréée de sécurité civile",
            "Autre agrément" => "Autre agrément",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE PUBLIQUE TYPES
    |--------------------------------------------------------------------------
    |
    */
    'structure_publique_types' => [
        "vocabulary" => "Types structure publique",
        "terms" => [
            "Collectivité territoriale" => "Collectivité territoriale",
            "Etablissement scolaire" => "Etablissement scolaire",
            "Etablissement public de santé" => "Etablissement public de santé",
            "Etablissement public" => "Etablissement public",
            "Service de l'Etat" => "Service de l'Etat",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | STRUCTURE PUBLIQUE ETAT TYPES
    |--------------------------------------------------------------------------
    |
    */
    'structure_publique_etat_types' => [
        "vocabulary" => "Types établissement publique",
        "terms" => [
            "SDIS (Service départemental d'Incendie et de Secours)" => "SDIS (Service départemental d'Incendie et de Secours)",
            "Gendarmerie" => "Gendarmerie",
            "Police" => "Police",
            "Armées" => "Armées",
            "Autre service de l'état" => "Autre service de l'état",
            "Autre établissement public" => "Autre établissement public",
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | STRUCTURE PRIVEE TYPES
    |--------------------------------------------------------------------------
    |
    */
    'structure_privee_types' => [
        "vocabulary" => "Types structure publique",
        "terms" => [
            "Établissement de santé privé d'intérêt collectif" => "Établissement de santé privé d'intérêt collectif",
            "Entreprise agréée ESUS" => "Entreprise agréée ESUS",
            "Autre" => "Autre",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | MISSION FORMATS
    |--------------------------------------------------------------------------
    |
    */
    'mission_formats' => [
        "vocabulary" => "Formats de mission",
        "terms" => [
            "Mission ponctuelle" => "Mission ponctuelle",
            "Mission récurrente" => "Mission récurrente",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | MISSION TYPES
    |--------------------------------------------------------------------------
    |
    */
    'mission_types' => [
        "vocabulary" => "Types de mission",
        "terms" => [
            "Mission en présentiel" => "Mission en présentiel",
            "Mission à distance" => "Mission à distance",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | MISSION PERIODES
    |--------------------------------------------------------------------------
    |
    */
    'mission_periodes' => [
        "vocabulary" => "Périodes",
        "terms" => [
            'Pendant les vacances scolaires' => "Pendant les vacances scolaires",
            'En-dehors des vacances scolaires (mercredi après-midi, soirées et/ou weekends)' => "En-dehors des vacances scolaires (mercredi après-midi, soirées et/ou weekends)",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | MISSION PERIODICITES
    |--------------------------------------------------------------------------
    |
    */
    'mission_periodicites' => [
        "vocabulary" => "Périodicités",
        "terms" => [
            "1 heure par jour" => "1 heure par jour",
            "2 heures par jour" => "2 heures par jour",
            "3 heures par jour" => "3 heures par jour",
            "4 heures par jour" => "4 heures par jour",
            "1 heure par semaine" => "1 heure par semaine",
            "2 heures par semaine" => "2 heures par semaine",
            "3 heures par semaine" => "3 heures par semaine",
            "4 heures par semaine" => "4 heures par semaine",
            "1 journée par semaine" => "1 journée par semaine",
            "2 journées par semaine" => "2 journées par semaine",
            "3 journées par semaine" => "3 journées par semaine",
            "4 journées par semaine" => "4 journées par semaine",
            "5 journées par semaine" => "5 journées par semaine",
            "10 jours par mois" => "10 jours par mois",
            "15 jours par mois" => "15 jours par mois",
            "20 jours par mois" => "20 jours par mois",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | MISSION DOMAINES
    |--------------------------------------------------------------------------
    |
    */
    'mission_domaines' => [
        "vocabulary" => "Domaines de mission",
        "terms" => [
            "Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis" => "Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis",
            "Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance" => "Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance",
            "Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)" => "Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)",
            "Je fais les courses de produits essentiels pour mes voisins les plus fragiles." => "Je fais les courses de produits essentiels pour mes voisins les plus fragiles.",
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | MISSION PUBLICS BENEFICIAIRES
    |--------------------------------------------------------------------------
    |
    */
    'mission_publics_beneficiaires' => [
        "vocabulary" => "Publics bénéficiaires",
        "terms" => [
            "Personnes âgées" => "Personnes âgées",
            "Personnes en situation de handicap" => "Personnes en situation de handicap",
            "Personnes à la rue" => "Personnes à la rue",
            "Parents" => "Parents",
            "Tous publics" => "Tous publics"
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | MISSION PUBLICS VOLONTAIRES
    |--------------------------------------------------------------------------
    |
    */
    'mission_publics_volontaires' => [
        "vocabulary" => "Publics volontaires",
        "terms" => [
            "Personnes mobiles" => "Personnes mobiles",
            "À determiner" => "À determiner"
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | Départements
    |--------------------------------------------------------------------------
    |
    */
    'departments' => [
        "vocabulary" => "Départements",
        "terms" => [
            "01" => "Ain",
            "02" => "Aisne",
            "03" => "Allier",
            "04" => "Alpes-de-Haute-Provence",
            "05" => "Hautes-Alpes",
            "06" => "Alpes-Maritimes",
            "07" => "Ardèche",
            "08" => "Ardennes",
            "09" => "Ariège",
            "10" => "Aube",
            "11" => "Aude",
            "12" => "Aveyron",
            "13" => "Bouches-du-Rhône",
            "14" => "Calvados",
            "15" => "Cantal",
            "16" => "Charente",
            "17" => "Charente-Maritime",
            "18" => "Cher",
            "19" => "Corrèze",
            "21" => "Côte-d'Or",
            "22" => "Côtes-d'Armor",
            "23" => "Creuse",
            "24" => "Dordogne",
            "25" => "Doubs",
            "26" => "Drôme",
            "27" => "Eure",
            "28" => "Eure-et-Loire",
            "29" => "Finistère",
            "2A" => "Corse-du-Sud",
            "2B" => "Haute-Corse",
            "30" => "Gard",
            "31" => "Haute-Garonne",
            "32" => "Gers",
            "33" => "Gironde",
            "34" => "Hérault",
            "35" => "Ille-et-Vilaine",
            "36" => "Indre",
            "37" => "Indre-et-Loire",
            "38" => "Isère",
            "39" => "Jura",
            "40" => "Landes",
            "41" => "Loir-et-Cher",
            "42" => "Loire",
            "43" => "Haute-Loire",
            "44" => "Loire-Atlantique",
            "45" => "Loiret",
            "46" => "Lot",
            "47" => "Lot-et-Garonne",
            "48" => "Lozère",
            "49" => "Maine-et-Loire",
            "50" => "Manche",
            "51" => "Marne",
            "52" => "Haute-Marne",
            "53" => "Mayenne",
            "54" => "Meurthe-et-Moselle",
            "55" => "Meuse",
            "56" => "Morbihan",
            "57" => "Moselle",
            "58" => "Nièvre",
            "59" => "Nord",
            "60" => "Oise",
            "61" => "Orne",
            "62" => "Pas-de-Calais",
            "63" => "Puy-de-Dôme",
            "64" => "Pyrénées-Atlantiques",
            "65" => "Hautes-Pyrénées",
            "66" => "Pyrénées-Orientales",
            "67" => "Bas-Rhin",
            "68" => "Haut-Rhin",
            "69" => "Rhône",
            "70" => "Haute-Saône",
            "71" => "Saône-et-Loire",
            "72" => "Sarthe",
            "73" => "Savoie",
            "74" => "Haute-Savoie",
            "75" => "Paris",
            "76" => "Seine-Maritime",
            "77" => "Seine-et-Marne",
            "78" => "Yvelines",
            "79" => "Deux-Sèvres",
            "80" => "Somme",
            "81" => "Tarn",
            "82" => "Tarn-et-Garonne",
            "83" => "Var",
            "84" => "Vaucluse",
            "85" => "Vendée",
            "86" => "Vienne",
            "87" => "Haute-Vienne",
            "88" => "Vosges",
            "89" => "Yonne",
            "90" => "Territoire de Belfort",
            "91" => "Essonne",
            "92" => "Hauts-de-Seine",
            "93" => "Seine-Saint-Denis",
            "94" => "Val-de-Marne",
            "95" => "Val-d'Oise",
            "971" => "Guadeloupe",
            "972" => "Martinique",
            "973" => "Guyane",
            "974" => "La Réunion",
            "976" => "Mayotte",
            "987" => "Polynésie française"
        ]
    ],

     /*
    |--------------------------------------------------------------------------
    | Régions
    |--------------------------------------------------------------------------
    |
    */
    'regions' => [
        "vocabulary" => "Régions",
        "terms" => [
            "Auvergne-Rhônes-Alpes" => "Auvergne-Rhônes-Alpes",
            "Bourgogne-Franche-Comté" => "Bourgogne-Franche-Comté",
            "Bretagne" => "Bretagne",
            "Centre-Val de Loire" => "Centre-Val de Loire",
            "Corse" => "Corse",
            "Grand Est" => "Grand Est",
            "Hauts-de-France" => "Hauts-de-France",
            "Île-de-France" => "Île-de-France",
            "Normandie" => "Normandie",
            "Nouvelle-Aquitaine" => "Nouvelle-Aquitaine",
            "Occitanie" => "Occitanie",
            "Pays de la Loire" => "Pays de la Loire",
            "Provence-Alpes-Côte d'Azur" => "Provence-Alpes-Côte d'Azur",
            "Guadeloupe" => "Guadeloupe",
            "Martinique" => "Martinique",
            "Guyane" => "Guyane",
            "La Réunion" => "La Réunion",
            "Mayotte" => "Mayotte",
        ],
        "departments" => [
            "Auvergne-Rhônes-Alpes" => ["03","42","63","15","43","07","26","38","73","74","69"],
            "Bourgogne-Franche-Comté" => ["89","21","70","90","25","39","71","58"],
            "Bretagne" => ["29","22","35","56"],
            "Centre-Val de Loire" => ["28","45","41","37","36","18"],
            "Corse" => ["2A","2B"],
            "Grand Est" => ["08","55","54","57","67","68","54","88","52","10"],
            "Hauts-de-France" => ["62","59","02","60","80"],
            "Île-de-France" => ["75","77","78","91","92","93","94","95"],
            "Normandie" => ["50","14","76","27","61"],
            "Nouvelle-Aquitaine" => ["79","86","17","16","87","23","33","24","19","40","47","64"],
            "Occitanie" => ["46","12","48","30","82","81","34","32","31","11","65","09","66"],
            "Pays de la Loire" => ["53","72","44","49","85"],
            "Provence-Alpes-Côte d'Azur" => ["05","04","84","13","06","83"],
            "Guadeloupe" => ["971"],
            "Martinique" => ["972"],
            "Guyane" => ["973"],
            "La Réunion" => ["974"],
            "Mayotte" => ["976"],
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | PARTICIPATION WORKFLOW STATES
    |--------------------------------------------------------------------------
    |
    */
    'participation_workflow_states' => [
        "vocabulary" => "Statut",
        "terms" => [
            "En attente de validation" => "En attente de validation",
            "Mission validée" => "Mission validée",
            "Mission en cours" => "Mission en cours",
            "Mission effectuée" => "Mission effectuée",
            //"Mission refusée" => "Mission refusée",
            "Mission abandonnée" => "Mission abandonnée",
            "Mission annulée" => "Mission annulée",
            "Mission signalée" => "Mission signalée",
           // "Archivée" => "Archivée"
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | COLLECTIVITIES TYPES
    |--------------------------------------------------------------------------
    |
    */
    'collectivities_types' => [
        "vocabulary" => "Types de collectivités",
        "terms" => [
            "Collectivité" => "Collectivité",
            "Autre" => "Autre"
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | COLLECTIVITIES STATES
    |--------------------------------------------------------------------------
    |
    */
    'collectivities_states' => [
        "vocabulary" => "Statut des collectivités",
        "terms" => [
            "En attente de validation" => "En attente de validation",
            "Validée" => "Validée",
            "Signalée" => "Signalée",
            "Archivée" => "Archivée",
        ]
    ],
];

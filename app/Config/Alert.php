<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Paramètres de configuration des messages d'alerte
 */
class Alert
{
    /**
     * Chemin vers le template d'alerte
     *
     * @var string
     */
    public static string $template = VIEWPATH . '_partials/alert.php';

    /**
     * Tableau des messages d'alerte
     *
     * @var array
     */
    public static array $messages = 
    [
        // Accouplements
        'coupling_created'                 => 'Accouplement enregistré avec succès',
        'coupling_gender_invalid'          => 'Les serpents doivent être de genre différent',
        'coupling_race_invalid'            => 'Les serpents doivent être de la même race',
        'coupling_no_data'                 => 'Données insuffisantes',
        'coupling_not_found'               => 'Serpents introuvables',
        'coupling_none'                    => 'Aucun accouplement à afficher',

        // Serpents
        'snake_id_posint_required'         => 'L\'identifiant unique du serpent doit être un entier positif',
        'snake_name_required'              => 'Le nom du serpent est obligatoire',
        'snake_weight_required'            => 'Le poids du serpent est obligatoire',
        'snake_weight_posint_required'     => 'Le poids du serpent doit être un entier positif',
        'snake_birth_date_required'        => 'La date et l\'heure de naissance du serpent sont obligatoires',
        'snake_birth_date_format_required' => 'La date et l\'heure de naissance du serpent doivent être au format jj/mm/aaaa hh:mm',
        'snake_birth_date_lte_now'         => 'La date et l\'heure de naissance doivent être inférieures ou égal à maintenant',
        'snake_lifespan_required'          => 'La durée de vie du serpent est obligatoire',
        'snake_lifespan_posint_required'   => 'La durée de vie du serpent doit être un entier positif',
        'snake_status_required'            => 'Le statut du serpent est obligatoire',
        'snake_race_required'              => 'La race du serpent est obligatoire',
        'snake_gender_required'            => 'Le genre du serpent est obligatoire',
        'snake_populate'                   => '%d nouveau serpent créé avec succès',
        'snake_populate_plural'            => '%d nouveaux serpents créés avec succès',
        'snake_created'                    => 'Serpent créé avec succès',
        'snake_updated'                    => 'Serpent modifié avec succès',
        'snake_deleted'                    => 'Serpent supprimé avec succès',
        'snake_none'                       => 'Aucun serpent à afficher',

        // Codes HTTP
        'error_404'                        => 'Nous sommes désolés mais la page que vous recherchez n\'a pu être trouvée',
        'error_405'                        => 'Méthode HTTP non autorisée pour cette route',
        'error_500'                        => 'Nous sommes désolés mais une erreur interne est survenue',

        // Erreurs génériques
        'retry_later'                      => 'Une erreur est survenue, veuillez essayer à nouveau ultérieurement'
    ];
}

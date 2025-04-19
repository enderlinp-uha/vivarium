<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Paramètres de connexion à la base de données
 */
class Database
{
    /**
     * Nom d'hôte de la base de données
     */
    public const HOST = 'localhost';

    /**
     * Nom de la base de données
     */
    public const NAME = 'vivarium';

    /**
     * Numéro de port de la base de données
     */
    public const PORT = 3306;

    /**
     * Jeu de caractères de la base de données 
     */
    public const CHARSET = 'utf8mb4';

    /**
     * Nom d'utilisateur de la base de données
     */
    public const USERNAME = 'root';

    /**
     * Mot de passe de la base de données
     */
    public const PASSWORD = '';
}

<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Paramètres de configuration de l'application
 */
class App 
{
    /**
     * URL de base de l'application
     */
    public const BASE_URL = 'http://localhost:8080/';

    /**
     * Nom de l'application
     */
    public const NAME = 'Vivarium';

    /**
     * Environnement de l'application
     */
    public const ENVIRONMENT = 'production';

    /**
     * Localisation
     */
    public const LOCALE = 'fr_FR';

    /**
     * Fuseau horaire
     */
    public const TIMEZONE = 'Europe/Paris';
}

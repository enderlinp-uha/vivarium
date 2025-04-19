<?php

declare(strict_types=1);

namespace App\Config;

/**
 * Paramètres de configuration de la pagination
 */
class Pagination
{
    /**
     * Nombre d'éléments par page par défaut
     */
    public const PER_PAGE_DEFAULT = 10;

    /**
     * Liste du nombre d'éléments par page
     */
    public const PER_PAGE_VALUES = [5, 10, 25, 50, 100];
}

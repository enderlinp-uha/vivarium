<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * Entité de l'objet serpent
 */
class SnakeEntity extends BaseEntity
{
    protected string $birth_date;
    protected int    $lifespan;
    protected int    $weight;
    protected ?int   $parent1_id = null;
    protected ?int   $parent2_id = null;

    /**
     * Fonction permettant de retourner l'espérance de vie avec un suffixe
     *
     * @param boolean $suffix
     * @return string
     */
    public function getFormattedLifespan(bool $suffix = true): string
    {
        $plural = $this->lifespan > 1 ? 's' : '';
        $str = $this->lifespan . ($suffix ? ' an' . $plural : '');

        return $str;
    }

    /**
     * Fonction permettant de retourner le poids formaté avec séparateur de milliers
     *
     * @return string
     */
    public function getFormattedWeight(): string
    {
        return number_format((int) $this->weight, 0, '', ' ') . ' g';
    }

    /**
     * Fonction permettant de convertir la date et l'heure de naissance
     *
     * @param string $strDate
     * @param string $strFrom
     * @return void
     */
    public function setBirthDate(string $strDate, string $strFrom): void
    {
        $this->birth_date = create_from_format($strFrom, $strDate);
    }
}

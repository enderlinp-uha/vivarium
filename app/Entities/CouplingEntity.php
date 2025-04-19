<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * Entité de l'objet accouplement
 */
class CouplingEntity extends BaseEntity
{
    protected int    $id_male;
    protected int    $id_female;
    protected int    $children_count;
    protected int    $max_children;
    protected string $last_reproduction_at;
    protected string $created_at;

    /**
     * Fonction permettant de formatter une date
     *
     * @param string $format
     * @return string
     */
    public function getFormattedDate(string $format = 'd/m/Y H:i'): string
    {
        $date = new \DateTimeImmutable($this->created_at);

        return $date->format($format);
    }
}

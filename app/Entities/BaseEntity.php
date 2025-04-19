<?php

declare(strict_types=1);

namespace App\Entities;

/**
 * Entité mère
 */
#[\AllowDynamicProperties]
abstract class BaseEntity
{
    /**
     * Accesseur
     *
     * @param string $strName
     * @return mixed
     */
    public function __get(string $strName): mixed
    {
        return property_exists($this, $strName) ? ($this->$strName ?? null) : null;
    }

    /**
     * Mutateur
     *
     * @param string $strName
     * @param mixed $mixedValue
     * @return void
     */
    public function __set(string $strName, mixed $mixedValue = ''): void
    {
        $this->$strName = is_numeric($mixedValue) ? (int) $mixedValue : esc_html($mixedValue);
    }
}

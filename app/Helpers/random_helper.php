<?php

declare(strict_types=1);

/*
| ----------------------------------------------------
| Fonctions de randomisation
| ----------------------------------------------------
*/

/**
 * Fonction permettant de randomiser une date/heure
 *
 * @param integer $max
 * @param string $format
 * @return string
 */
function random_datetime(int $max, string $format = 'Y-m-d H:i:00'): string
{
    if ($max == 1) {
        $month = random_number_between(1, 11);
        $interval = "P{$month}M";
    } else {
        $year = random_number_between(1, $max - 1);
        $interval = "P{$year}Y";
    }

    $date = new DateTime();
    $date->sub(new DateInterval($interval));

    return $date->format($format);
}

/**
 * Fonction permettant de randomiser un tableau
 *
 * @param array $array
 * @return string
 */
function random_element(array $array): string
{
    if ($array === []) return null;

    return $array[array_rand($array, 1)];
}

/**
 * Fonction permettant de randomiser un nombre entier
 *
 * @return integer
 */
function random_number(): int
{
    return mt_rand();
}

/**
 * Fonction permettant de randomiser un nombre entier entre 2 bornes
 *
 * @param integer $min
 * @param integer $max
 * @return integer
 */
function random_number_between(int $min, int $max): int
{
    return mt_rand($min, $max);
}

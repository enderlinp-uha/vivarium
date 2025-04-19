<?php

declare(strict_types=1);

use App\Config\Provider;

// Chargement des fonctions de randomisation
require_once(APPPATH . 'Helpers/random_helper.php');

/*
| ----------------------------------------------------
| Fonctions de création de jeux de données
| ----------------------------------------------------
*/

/**
 * Fonction permettant de fournir une date de naissance aléatoire
 *
 * @param integer $lifespan
 * @return string
 */
function provider_birth_date(int $lifespan): string
{
    return random_datetime($lifespan);
}

/**
 * Fonction permettant de récupérer la fertilité maximale pour une race
 *
 * @param string $race
 * @return integer
 */
function provider_fertility(string $race): int
{
    $range = Provider::$fertility_by_race[$race] ?? Provider::$fertility_default;
    return random_number_between($range[0], $range[1]);
}

/**
 * Fonction permettant de fournir un genre aléatoire
 *
 * @return string
 */
function provider_gender(): string
{
    return random_element(Provider::$gender);
}

/**
 * Fonction permettant de fournir une durée de vie aléatoire
 *
 * @return int
 */
function provider_lifespan(): int
{
    return random_number_between(
        Provider::$lifespan[0],
        Provider::$lifespan[1]
    );
}

/**
 * Fonction permettant de récupérer le nombre maximum de serpents à générer
 *
 * @return integer
 */
function provider_max_to_generate(): int
{
    return Provider::$max_to_generate;
}

/**
 * Fonction permettant de fournir un nom de serpent aléatoire
 *
 * @param boolean $suffix
 * @return string
 */
function provider_name(bool $suffix = true, string $separator = '-'): string
{
    $name = random_element(Provider::$name);
    
    if ($suffix) {
        $name .= $separator . random_number_between(100, 999);
    }

    return $name;
}

/**
 * Fonction permettant de fournir une race de serpent aléatoire
 *
 * @return string
 */
function provider_race(): string
{
    return random_element(array_keys(Provider::$fertility_by_race));
}

/**
 * Fonction permettant de etourner l’intervalle minimum en minutes entre deux reproductions d’un même couple
 *
 * @return int
 */
function provider_reproduction_interval(): int
{
    return Provider::$reproduction_interval;
}

/**
 * Fonction permettant de fournir un poids aléatoire
 *
 * @return int
 */
function provider_weight(): int
{
    return random_number_between(
        Provider::$weight[0],
        Provider::$weight[1]
    );
}

/**
 * Fonction permettant de construire un tableau de caractéristiques de serpent randomisées
 *
 * @return array
 */
function provider_build(): array
{
    $data['name']       = provider_name();
    $data['weight']     = provider_weight();
    $data['lifespan']   = provider_lifespan();
    $data['birth_date'] = provider_birth_date($data['lifespan']);
    $data['race']       = provider_race();
    $data['gender']     = provider_gender();

    return $data;
}

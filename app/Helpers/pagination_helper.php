<?php

declare(strict_types=1);

/*
| ----------------------------------------------------
| Fonctions de pagination
| ----------------------------------------------------
*/

/**
 * Fonction de permettant de retourner l'URL de la première page (pagination)
 *
 * @return string
 */
function pagination_first(): string 
{
    return pagination_url(1);
}

/**
 * Fonction de permettant de retourner l'URL de la dernière page (pagination)
 *
 * @param integer $page
 * @return string
 */
function pagination_last(int $page): string 
{
    return pagination_url($page);
}

/**
 * Fonction de permettant de retourner l'URL de la page suivante (pagination)
 *
 * @param integer $page
 * @return string
 */
function pagination_next(int $page): string 
{
    return pagination_url($page + 1);
}

/**
 * Fonction de permettant de retourner l'URL de la page précédente (pagination)
 *
 * @param integer $page
 * @return string
 */
function pagination_previous(int $page): string 
{
    return pagination_url($page - 1);
}

/**
 * Fonction de permettant de retourner l'URL de la page en cours (pagination)
 *
 * @param integer $page
 * @return string
 */
function pagination_url(int $page): string
{
    $url = preg_replace('/([?&]page=\d+)/', '', current_url());
    $sep = strpos($url, '?') !== false ? '&' : '?';
    
    return sprintf('%s%spage=%d', $url, $sep, $page);
}

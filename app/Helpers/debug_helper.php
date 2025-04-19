<?php

declare(strict_types=1);

/*
| ----------------------------------------------------
| Fonctions de débogage
| ----------------------------------------------------
*/

/**
 * Fonction permettant d'afficher les informations d'une variable de manière pré-formattée
 *
 * @param mixed $expression
 * @return void
 */
function echo_r(mixed $expression): void
{
    $str = print_r($expression);

    echo "<pre>$str</pre>";
}

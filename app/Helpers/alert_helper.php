<?php 

declare(strict_types=1);

/*
| ----------------------------------------------------
| Fonctions d'alertes
| ----------------------------------------------------
*/

/**
 * Fonction permettant d'afficher des alertes Bootstrap
 *
 * @return void
 */
function alert(): void
{
    if (isset($_SESSION['message']))
    {
        [$strMessage, $strClass] = $_SESSION['message'];
        $arrMessages = is_array($strMessage) ? $strMessage : array($strMessage);

        if (count($arrMessages) > 0) {
            include(\App\Config\Alert::$template);
        }        

        unset($_SESSION['message']);
    }
}

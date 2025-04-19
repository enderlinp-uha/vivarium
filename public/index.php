<?php

declare(strict_types=1);

ob_start();

// Chargement de l'autoload de Composer
require(__DIR__ . '/../vendor/autoload.php');

use App\Config\App;

// Démarrage de la session
session_start([
    'use_strict_mode' => true,
    'cookie_httponly' => true,
    'cookie_secure'   => isset($_SERVER['HTTPS']),
    'cookie_samesite' => 'Strict'
]);

// Version minimum de PHP requise
$strMinPhpVersion = '8.0';
if (version_compare(PHP_VERSION, $strMinPhpVersion, '<')) 
{
    $message = sprintf(
        'Votre version de PHP doit être au moins égale à %s. Votre version de PHP est : %s',
        $strMinPhpVersion,
        PHP_VERSION
    );

    exit($message);
}

// Chemins de l'application
define('APPPATH',  realpath(__DIR__ . '/../app') . DIRECTORY_SEPARATOR);
define('VIEWPATH', realpath(APPPATH . 'Views')   . DIRECTORY_SEPARATOR);

if (! is_dir(APPPATH)) {
    exit('Le chemin de l\'application est invalide');
}

// Chargement des constantes de l'application
require(APPPATH . 'Config/App.php');

// Chargement de la gestion des erreurs de l'application
require(APPPATH . 'Config/Errors.php');

// Chargement des fonctions transversales de l'application
require(APPPATH . 'common.php');

// Chargement de l'amorçage
require(APPPATH . 'bootstrap.php');

// Réglage du fuseau horaire par défaut
date_default_timezone_set(App::TIMEZONE);

// Réglage de la localisation
setlocale(LC_ALL, App::LOCALE);

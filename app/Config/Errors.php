<?php

declare(strict_types=1);

namespace App\Config;

use App\Config\App;

/*
| ----------------------------------------------------
| Gestion des erreurs
| ----------------------------------------------------
*/
$logPath = __DIR__ . '/../../writable/logs/php_errors.log';

if (App::ENVIRONMENT === 'production') 
{
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', $logPath);
    error_reporting(E_ALL & ~E_USER_DEPRECATED);
} 
else 
{
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    ini_set('log_errors', '1');
    ini_set('error_log', $logPath);
    error_reporting(-1);
}

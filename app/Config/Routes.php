<?php

declare(strict_types=1);

namespace App\Config;

use App\Controllers\SnakeController;

defined('DEFAULT_ROUTE') || define('DEFAULT_ROUTE', [SnakeController::class, 'index']);

return [
    ['GET',    '/',                 DEFAULT_ROUTE],
    ['GET',    '/list',             [SnakeController::class, 'index']],
    ['GET',    '/add',              [SnakeController::class, 'add']],
    ['POST',   '/add',              [SnakeController::class, 'add']],
    ['GET',    '/edit/{id}',        [SnakeController::class, 'edit']],
    ['POST',   '/edit/{id}',        [SnakeController::class, 'edit']],
    ['DELETE', '/delete/{id}',      [SnakeController::class, 'delete']],
    ['GET',    '/family_tree/{id}', [SnakeController::class, 'family_tree']],
    ['GET',    '/coupling',         [SnakeController::class, 'couplings']],
    ['POST',   '/coupling',         [SnakeController::class, 'coupling']],
    ['GET',    '/populate',         [SnakeController::class, 'populate']],
    ['PATCH',  '/reproduce',        [SnakeController::class, 'reproduce']],
    ['PATCH',  '/update_status',    [SnakeController::class, 'update_status']]
];

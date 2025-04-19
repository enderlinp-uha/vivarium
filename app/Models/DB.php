<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;
use PDO;

/**
 * Classe mère DB
 */
abstract class DB
{
    protected object $db;

    public function __construct()
    {
        try 
        {
            $this->db = new PDO(
                'mysql:host=' . Database::HOST . 
                ';dbname='    . Database::NAME . 
                ';port='      . Database::PORT . 
                ';charset='   . Database::CHARSET, 
                Database::USERNAME, 
                Database::PASSWORD
            );

            $this->db->setAttribute(PDO::ATTR_ERRMODE,            PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } 
        catch (PDOException $e) 
        {
            if (\App\Config\App::ENVIRONMENT === 'development') {
                die(e->getMessage());
            } else {
                error_page(500);
            }
        }
    }
}

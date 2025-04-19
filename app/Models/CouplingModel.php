<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * Modèle de l'objet 'accouplement'
 */
class CouplingModel extends BaseModel
{
    protected array $protectedFields = [
        'id_coupling',
        'id_male',
        'id_female',
        'children_count',
        'max_children',
        'last_reproduction_at',
        'created_at',
    ];

    protected bool $useSoftDeletes = false;

    public function __construct()
    {
        parent::__construct();

        $this->table  = 'couplings';
        $this->suffix = '_coupling';
    }

    /**
     * Fonction permettant de retourner la liste des accouplements
     *
     * @return array
     */
    public function getCouplings(): array
    {
        $strQuery = "SELECT c.*, sm.name AS male_name, sf.name AS female_name
                     FROM `$this->table` AS c
                     JOIN snakes sm ON sm.id_snake = c.id_male
                     JOIN snakes sf ON sf.id_snake = c.id_female";
        
        if (count($this->orderBy) > 0)
        {
            [$strField, $strOrder] = $this->orderBy;
            $strQuery .= " ORDER BY `{$strField}` $strOrder";
        }

        if (count($this->limit) > 0)
        {
            $strQuery .= " LIMIT :limit OFFSET :offset";
            [$intLimit, $intOffset] = $this->limit;
            
            $arrParams = array(
                array(':limit',  $intLimit,  PDO::PARAM_INT),
                array(':offset', $intOffset, PDO::PARAM_INT)
            );
        }

        return $this->queryAll($strQuery, $arrParams);
    }

    /**
     * Fonction permettant d'insérer un nouvel accouplement
     *
     * @param object $objSnake
     * @return object|boolean
     */
    public function insert(object $objSnake): object|bool
    {
        $strPrepQuery = "INSERT INTO `$this->table` 
                         (`id_male`, `id_female`, `children_count`, `max_children`, `created_at`) 
                         VALUES (:id_male, :id_female, :children_count, :max_children, NOW())";

        $arrParams = array(
            array(':id_male',        $objSnake->id_male,        PDO::PARAM_INT),
            array(':id_female',      $objSnake->id_female,      PDO::PARAM_INT),
            array(':children_count', $objSnake->children_count, PDO::PARAM_INT),
            array(':max_children',   $objSnake->max_children,   PDO::PARAM_INT)
        );

        return $this->query($strPrepQuery, $arrParams);
    }
}

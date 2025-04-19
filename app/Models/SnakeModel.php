<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * Modèle de l'objet 'serpent'
 */
class SnakeModel extends BaseModel
{
    protected array $protectedFields = [
        'id_snake',
        'parent1_id',
        'parent2_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function __construct()
    {
        parent::__construct();

        $this->table  = 'snakes';
        $this->suffix = '_snake';
    }

    /**
     * Fonction permettant la reproduction automatique entre couples de même race
     * 
     * @return integer
     */
    public function autoReproduce(): int
    {
        helper('provider');
    
        // Sélection des couples
        $strPrepQuery = "SELECT c.*, m.race AS male_race, f.race AS female_race
                         FROM couplings c
                         JOIN snakes m ON c.id_male = m.id_snake
                         JOIN snakes f ON c.id_female = f.id_snake
                         WHERE m.status = 'Vivant'
                             AND f.status = 'Vivant'
                             AND m.race = f.race
                             AND c.children_count < c.max_children
                             AND (
                                 c.last_reproduction_at IS NULL
                                 OR TIMESTAMPDIFF(MINUTE, c.last_reproduction_at, NOW()) >= :interval
                         )";
    
        $arrParams = array(
            array(':interval', provider_reproduction_interval(), PDO::PARAM_INT)
        );
    
        $couples = $this->queryAll($strPrepQuery, $arrParams);
    
        // Insertion de nouveaux enfants
        $generated = 0;
        foreach ($couples as $couple) {
            $remaining = $couple->max_children - $couple->children_count;
            if ($remaining <= 0) continue;
    
            $snake = new \App\Entities\SnakeEntity();
            $snake->name        = provider_name();
            $snake->weight      = provider_weight();
            $snake->lifespan    = provider_lifespan();
            $snake->birth_date  = (new \DateTime())->format('Y-m-d H:i');
            $snake->race        = $couple->male_race;
            $snake->gender      = provider_gender();
            $snake->parent1_id  = $couple->id_male;
            $snake->parent2_id  = $couple->id_female;
            
            $this->insert($snake);
    
            // Mise à jour du couple
            $strPrepQuery = "UPDATE couplings
                             SET children_count = children_count + 1, last_reproduction_at = NOW()
                             WHERE id_coupling = :id_coupling";

            $arrParams = array(
                array(':id_coupling', $couple->id_coupling, PDO::PARAM_INT)
            );

            $this->query($strPrepQuery, $arrParams);
            $generated++;
        }
    
        return $generated;
    }    

    /**
     * Fonction permettant de retourner la liste de races de serpents 
     *
     * @return array|boolean
     */
    public function getCurrentRaces(): array|bool
    {
        $strQuery = "SELECT DISTINCT `race` 
                     FROM `$this->table`
                     WHERE `deleted_at` IS NULL
                     ORDER BY `race`";
        
        return $this->queryAll($strQuery);
    }

    /**
     * Fonction permettant de retourner le nombre de serpents selon leur genre
     *
     * @return array|boolean
     */
    public function getGenderCount(): array|bool
    {
        $strQuery = "SELECT `gender` AS gender, COUNT(*) AS count 
                     FROM `$this->table` 
                     WHERE `deleted_at` IS NULL
                     GROUP BY `gender`";

        return $this->queryAll($strQuery);
    }

    /**
     * Fonction permettant d'insérer un nouveau serpent
     *
     * @param object $objSnake
     * @return object|boolean
     */
    public function insert(object $objSnake): object|bool
    {
        $strPrepQuery = "INSERT INTO `$this->table` 
                        (`name`, `weight`, `lifespan`, `birth_date`, `race`, `gender`, `parent1_id`, `parent2_id`)
                        VALUES (:name, :weight, :lifespan, :birth_date, :race, :gender, :parent1_id, :parent2_id)";
        
        if (strpos($objSnake->birth_date, '/') !== false) {
            $objSnake->setBirthDate($objSnake->birth_date, 'd/m/Y H:i');
        }

        $arrParams = array(
            array(':name',       $objSnake->name,       PDO::PARAM_STR),
            array(':weight',     $objSnake->weight,     PDO::PARAM_INT),
            array(':lifespan',   $objSnake->lifespan,   PDO::PARAM_INT),
            array(':birth_date', $objSnake->birth_date, PDO::PARAM_STR),
            array(':race',       $objSnake->race,       PDO::PARAM_STR),
            array(':gender',     $objSnake->gender,     PDO::PARAM_STR),
            array(':parent1_id', $objSnake->parent1_id, PDO::PARAM_INT),
            array(':parent2_id', $objSnake->parent2_id, PDO::PARAM_INT)
        );

        return $this->query($strPrepQuery, $arrParams);
    }

    /**
     * Fonction de mise à jour des caractéristiques d'un serpent
     *
     * @param object $objSnake
     * @return object|boolean
     */
    public function update(object $objSnake): object|bool
    {
        $strPrepQuery = "UPDATE `$this->table` 
                         SET 
                            `name`       = :name, 
                            `weight`     = :weight, 
                            `lifespan`   = :lifespan, 
                            `birth_date` = :birth_date, 
                            `status`     = :status, 
                            `race`       = :race, 
                            `gender`     = :gender,
                            `updated_at` = NOW()
                        WHERE `id{$this->suffix}` = :id";
        
        $objSnake->setBirthDate($objSnake->birth_date, 'd/m/Y H:i');

        $arrParams = array(
            array(':name',       $objSnake->name,       PDO::PARAM_STR),
            array(':weight',     $objSnake->weight,     PDO::PARAM_INT),
            array(':lifespan',   $objSnake->lifespan,   PDO::PARAM_INT),
            array(':birth_date', $objSnake->birth_date, PDO::PARAM_STR),
            array(':status',     $objSnake->status,     PDO::PARAM_STR),
            array(':race',       $objSnake->race,       PDO::PARAM_STR),
            array(':gender',     $objSnake->gender,     PDO::PARAM_STR),
            array(':id',         $objSnake->id,         PDO::PARAM_INT)
        );

        return $this->query($strPrepQuery, $arrParams);
    }

    /**
     * Fonction permettant de mettre à jour du statut du serpent
     *
     * @return object|boolean
     */
    public function updateStatus(): object|bool
    {
        $strQuery = "UPDATE `$this->table`
                     SET `status` = 'Mort', `updated_at` = NOW()
                     WHERE `status` = 'Vivant'
                        AND DATE_ADD(`birth_date`, INTERVAL `lifespan` YEAR) < NOW()";

        return $this->query($strQuery);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * Modèle de base de données
 */
abstract class BaseModel extends DB
{
    protected string $field;
    protected string $table;
    protected string $suffix;

    protected bool   $useSoftDeletes  = true;

    protected array  $where           = [];
    protected array  $limit           = [];
    protected array  $orderBy         = [];
    protected array  $protectedFields = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fonction permettant de retourner le nombre total d'enregistrements
     *
     * @return integer
     */
    public function countAll(): int
    {
        $strQuery = "SELECT COUNT(*) FROM `$this->table`";

        if ($this->useSoftDeletes === true) 
        {
            $strQuery .= " WHERE `deleted_at` IS NULL";
        }

        $strPrepQuery = $this->db->prepare($strQuery);
        $strPrepQuery->execute();

        return $strPrepQuery->fetchColumn();
    }

    /**
     * Fonction de retourner le nombre de pages filtrées
     *
     * @return integer
     */
    public function countFiltered(): int
    {
        $boolWhere = false;
        $strQuery = "SELECT COUNT(*) FROM `$this->table`";

        if ($this->useSoftDeletes === true) {
            $strQuery .= " WHERE `deleted_at` IS NULL";
            $boolWhere = true;
        }

        $arrParams = array();
        if (count($this->where) > 0) {
            $strQuery .= $boolWhere ? " AND" : " WHERE";
            $arrWhere = [];

            foreach ($this->where as $key => $array) {
                [$strField, $mixedValue] = $array;
                $arrWhere[] = "`{$strField}` = :value{$key}";
                $arrParams[] = [":value{$key}", $mixedValue, \PDO::PARAM_STR];
            }

            $strQuery .= ' ' . implode(' AND ', $arrWhere);
        }

        $strPrepQuery = $this->db->prepare($strQuery);
        
        foreach ($arrParams as $arrParam) {
            $strPrepQuery->bindValue(...$arrParam);
        }

        $strPrepQuery->execute();
        
        return $strPrepQuery->fetchColumn();
    }

    /**
     * Fonction permettant de retourner le nombre de pages (pagination)
     *
     * @param integer $intPerPage
     * @return integer
     */
    public function countPages(int $intPerPage): int
    {
        return max(1, intval(ceil($this->countFiltered() / $intPerPage)));
    }

    /**
     * Fonction permettant de supprimer un enregistrement
     *
     * @param integer $intId
     * @return boolean
     */
    public function delete(int $intId): bool
    {
        if ($this->useSoftDeletes === true)
        {
            $strQuery = "UPDATE `$this->table` 
                         SET `deleted_at` = NOW() 
                         WHERE `id{$this->suffix}` = :id";
        }
        else
        {
            $strQuery = "DELETE FROM `$this->table` 
                         WHERE `id{$this->suffix}` = :id";
        }

        $arrParams = array(
            array(':id', $intId, PDO::PARAM_INT)
        );

        if ($this->query($strQuery, $arrParams)) 
        {
            return true;
        }

        return false;
    }

    /**
     * Fonction permettant de retourner les informations d'un objet selon son identifiant unique
     *
     * @param integer $intId
     * @return array|object|boolean
     */
    public function findById(int $intId): array|object|bool
    {
        $strQuery = "SELECT * FROM `$this->table` WHERE `id{$this->suffix}` = :id";

        $arrParams = array(
            array(':id', $intId, PDO::PARAM_INT)
        );

        return $this->queryOne($strQuery, $arrParams);
    }

    /**
     * Fonction permettant de retourner les informations d'un objet de maniètre paramétrée
     *
     * @return array|boolean
     */
    public function findAll(): array|bool
    {
        $boolWhere = false;
        
        $strQuery = "SELECT * FROM `$this->table`";
        
        if ($this->useSoftDeletes === true) 
        {
            if ($boolWhere === false)
            {
                $strQuery .= " WHERE";
                $boolWhere = true;
            }
            else
            {
                $strQuery .= " AND";
            }
            
            $strQuery .= " `deleted_at` IS NULL";
        }

        $arrParams = array();
        if (count($this->where) > 0)
        {
            if ($boolWhere === false)
            {
                $strQuery .= " WHERE";
                $boolWhere = true;
            }
            else
            {
                $strQuery .= " AND";
            }

            $arrWhere = array();
            foreach($this->where as $key => $array)
            {
                [$strField, $mixedValue] = $array;
                array_push($arrWhere, " `{$strField}` = :value{$key}");
                array_push($arrParams, array(":value{$key}", $mixedValue, PDO::PARAM_STR));
            }

            $strQuery .= implode(' AND ', $arrWhere);
        }
        
        if (count($this->orderBy) > 0)
        {
            [$strField, $strOrder] = $this->orderBy;
            $strQuery .= " ORDER BY `{$strField}` $strOrder";
        }

        if (count($this->limit) > 0)
        {
            $strQuery .= " LIMIT :limit OFFSET :offset";
            [$intLimit, $intOffset] = $this->limit;

            array_push($arrParams, array(':limit',  $intLimit,  PDO::PARAM_INT));
            array_push($arrParams, array(':offset', $intOffset, PDO::PARAM_INT));
        }

        return $this->queryAll($strQuery, $arrParams);
    }

    /**
     * Fonction permettant de retourner la liste des champs d'une table
     *
     * @return array
     */
    public function getFields(): array
    {
        $strQuery = "SELECT `COLUMN_NAME` 
                     FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                     WHERE `TABLE_NAME` = :table AND NOT FIND_IN_SET(`COLUMN_NAME`, :array)";

        $arrParams = array(
            array(':table', $this->table, PDO::PARAM_STR),
            array(':array', implode(', ', $this->protectedFields))
        );

        $objResult = $this->queryAll($strQuery, $arrParams);

        $arrResult = array();
        foreach($objResult as $objRow)
        {
            array_push($arrResult, $objRow->COLUMN_NAME);
        }

        return $arrResult;
    }

    /**
     * Fonction permettant de retourner le nombre de pages (pagination)
     *
     * @param integer $intPerPage
     * @return array
     */
    public function getPages(int $intPerPage): array
    {
        $arrPages = array();
        
        foreach(range(1, $this->countPages($intPerPage)) as $intPage) {
            array_push($arrPages, $intPage);
        }
        
        return $arrPages;
    }

    /**
     * Fonction permettant de limiter le nombre de résultats d'une requête paramétrée
     *
     * @param integer $intLimit
     * @param integer $intOffset
     * @return object
     */
    public function limit(int $intLimit, int $intOffset = 0): object
    {
        $this->limit = [$intLimit, $intOffset];

        return $this;
    }

    /**
     * Fonction permettant de retourner une liste sous la forme d'un tableau
     *
     * @param string $strField
     * @return array
     */
    public function list(string $strField): array
    {
        $arrResult = array();
        if (in_array($strField, $this->getFields()))
        {
            $strPrepQuery = "SELECT `id{$this->suffix}`, `$strField` FROM `$this->table`";

            foreach($this->queryAll($strPrepQuery) as $objResult) {
                $key   = $objResult->{'id' . $this->suffix};
                $value = $objResult->{$strField};
                
                $arrResult[$key] = $value;
            }
        }
        
        return $arrResult;
    }

    /**
     * Fonction permettant de trier les résultats d'une requête paramétrée
     *
     * @param string $strColumn
     * @param string $strOrder
     * @return object
     */
    public function orderBy(string $strColumn, string $strOrder): object
    {
        if (in_array($strColumn, $this->getFields()) && in_array($strOrder, ['ASC', 'DESC']))
        {
            $this->orderBy = [$strColumn, $strOrder];
        }

        return $this;
    }

    /**
     * Fonction permettant d'exécuter une requête préparée
     *
     * @param string $strQuery
     * @param array $arrParams
     * @param boolean $boolInsertId
     * @return object|boolean
     */
    public function query(string $strQuery, array $arrParams = array(), bool $boolInsertId = false): object|bool
    {
        $strPrepQuery = $this->db->prepare($strQuery);

        if (count($arrParams) > 0) 
        {
            foreach ($arrParams as $arrParam) 
            {
                $strPrepQuery->bindValue(...$arrParam);
            }
        }

        $strPrepQuery->execute();

        if ($boolInsertId === true)
        {
            $boolResult = $this->db->lastInsertId();
        }
        else 
        {
            $boolResult = $strPrepQuery;
        }

        return $boolResult;
    }

    /**
     * Fonction permettant de retourner les informations de plusieurs enregistrements
     *
     * @param string $strQuery
     * @param array $arrParams
     * @return array|boolean
     */
    public function queryAll(string $strQuery, array $arrParams = array()): array|bool
    {
        $strPrepQuery = $this->query($strQuery, $arrParams);
        
        return $strPrepQuery->fetchAll();
    }

    /**
     * Fonction permettant de retourner les informations d'un seul enregistrement
     *
     * @param string $strQuery
     * @param array $arrParams
     * @return array|object|boolean
     */
    public function queryOne(string $strQuery, array $arrParams = array()): array|object|bool
    {
        $strPrepQuery = $this->query($strQuery, $arrParams);
        
        return $strPrepQuery->fetch();
    }

    /**
     * Fonction permettant d'effectuer des requêtes paramétrées
     *
     * @param array $arrWhere
     * @return object
     */
    public function where(array $arrWhere): object
    {
        foreach($arrWhere as $strColumn => $mixedValue)
        {
            if (in_array($strColumn, $this->getFields()) && !in_array($mixedValue, [0, '']))
            {
                array_push($this->where, [$strColumn, $mixedValue]);
            }
        }

        return $this;
    }

    /**
     * Fonction permettant d'inclure les enregistrements supprimés
     *
     * @return object
     */
    public function withDeleted(): object
    {
        $this->useSoftDeletes = false;

        return $this;
    }
}

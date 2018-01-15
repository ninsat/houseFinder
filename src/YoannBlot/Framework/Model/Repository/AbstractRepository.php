<?php

namespace YoannBlot\Framework\Model\Repository;

use YoannBlot\Framework\Model\DataBase\Connector;
use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Exception\EntityNotFoundException;
use YoannBlot\Framework\Model\Exception\QueryException;
use YoannBlot\Framework\Utils\Log\Log;

/**
 * Class AbstractRepository
 *
 * @package YoannBlot\Framework\Model\Repository
 * @author  Yoann Blot
 */
abstract class AbstractRepository {

    /**
     * @return string current entity class.
     */
    public function getEntityClass(): string
    {
        $sClassName = get_class($this);
        $sClassName = substr($sClassName, 0, strrpos($sClassName, 'Repository'));
        $sClassName = str_replace('\\Repository\\', '\\Entity\\', $sClassName);

        return $sClassName;
    }

    /**
     * Try to get the @table annotation in Repository class comment.
     * If not found, then take the Repository class name as table name.
     *
     * @return string table name.
     */
    public function getTable (): string {
        $oReflectionClass = new \ReflectionClass($this);
        $oDocComment = $oReflectionClass->getDocComment();
        preg_match_all('#@table (.*)\n#s', $oDocComment, $aTable);

        if (count($aTable[1]) > 0) {
            $sTableName = trim($aTable[1][0]);
        } else {
            $sTableName = $this->getEntityClass();
            $sTableName = substr($sTableName, strrpos($sTableName, '\\') + 1);
            $sTableName = strtolower($sTableName);
        }

        return $sTableName;
    }

    /**
     * Get all values matching $sWhere, ordering by $sOrderBy, and limited result to $iLimit.
     *
     *
     * @param string $sWhere   where clause to filter values.
     * @param string $sOrderBy order by field with direction, i.e. 'date DESC'
     * @param int    $iLimit   maximum amount of data to retrieve.
     *
     * @return AbstractEntity[] all entities.
     */
    public function getAll (string $sWhere = '', string $sOrderBy = '', int $iLimit = 0): array {
        $sQuery = '';
        $sQuery .= "select * from {$this->getTable()} ";
        if ('' !== $sWhere) {
            $sQuery .= " $sWhere ";
        }
        if ('' !== $sOrderBy) {
            $sQuery .= " ORDER BY $sOrderBy ";
        }
        if (0 !== $iLimit) {
            $sQuery .= " LIMIT $iLimit";
        }

        try {
            $aEntities = Connector::get()->queryMultiple($sQuery, $this->getEntityClass());
        } catch (QueryException $oException) {
            Log::get()->error($oException->getMessage());
            $aEntities = [];
        }

        return $aEntities;
    }

    /**
     * Get a single entity by its id.
     *
     * @param int $iId entity id to retrieve.
     *
     * @return AbstractEntity matched entity.
     * @throws EntityNotFoundException if entity was not found.
     * @throws QueryException error in query.
     */
    public function get (int $iId) : AbstractEntity {
        $sQuery = '';
        $sQuery .= "select * from " . $this->getTable() . " where id = $iId limit 1";

        return Connector::get()->querySingle($sQuery, $this->getEntityClass());
    }
}
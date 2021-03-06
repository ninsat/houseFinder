<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Repository;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Psr\Log\LoggerInterface;
use YoannBlot\Framework\Model\DataBase\Annotation\Exclude;
use YoannBlot\Framework\Model\DataBase\Annotation\TableName;
use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Exception\EntityNotFoundException;
use YoannBlot\Framework\Model\Exception\QueryException;
use YoannBlot\Framework\Service\DatabaseConnector\ConnectorInterface;
use YoannBlot\Framework\Service\DatabaseConnector\ConnectorTrait;
use YoannBlot\Framework\Service\DatabaseCreation\RelationshipService;
use YoannBlot\Framework\Service\DatabaseCreation\RelationshipTrait;
use YoannBlot\Framework\Service\Logger\LoggerTrait;

/**
 * Class AbstractRepository
 *
 * @package YoannBlot\Framework\Model\Repository
 * @author  Yoann Blot
 */
abstract class AbstractRepository
{

    use LoggerTrait, ConnectorTrait, RelationshipTrait;

    /**
     * AbstractRepository constructor.
     *
     * @param LoggerInterface $oLoggerService logger.
     * @param ConnectorInterface $oConnectorService connector.
     * @param RelationshipService $oRelationshipService relationship service.
     */
    public function __construct(
        LoggerInterface $oLoggerService,
        ConnectorInterface $oConnectorService,
        RelationshipService $oRelationshipService
    ) {
        $this->oLogger = $oLoggerService;
        $this->oConnector = $oConnectorService;
        $this->oRelationshipService = $oRelationshipService;
    }

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
     * Try to get the "TableName" annotation in Repository class comment.
     * If not found, then take the Repository class name as table name.
     *
     * @return string table name.
     */
    public function getTableName(): string
    {
        $sTableName = 'fake';
        try {
            $oAnnotationReader = new AnnotationReader();
            /** @var TableName $oTableAnnotation */
            $oTableAnnotation = $oAnnotationReader->getClassAnnotation(new \ReflectionClass($this), TableName::class);
            if (null !== $oTableAnnotation) {
                $sTableName = $oTableAnnotation->getName();
            }
        } catch (AnnotationException $oException) {
            $this->getLogger()->error($oException->getMessage());
        } catch (\ReflectionException $oException) {
            $this->getLogger()->error($oException->getMessage());
        }

        return $sTableName;
    }

    /**
     * Get all values matching $sWhere, ordering by $sOrderBy, and limited result to $iLimit.
     *
     *
     * @param string $sWhere where clause to filter values.
     * @param string $sOrderBy order by field with direction, i.e. 'date DESC'
     * @param int $iLimit maximum amount of data to retrieve.
     *
     * @return AbstractEntity[] all entities.
     */
    public function getAll(string $sWhere = '', string $sOrderBy = '', int $iLimit = 0): array
    {
        $sQuery = '';
        $sQuery .= "select * from {$this->getTableName()} ";
        if ('' !== $sWhere) {
            $sQuery .= " $sWhere ";
        }
        if ('' !== $sOrderBy) {
            $sQuery .= " ORDER BY $sOrderBy ";
        }
        if (0 !== $iLimit) {
            $sQuery .= " LIMIT $iLimit";
        }

        return $this->getRelationshipService()->getEntities($sQuery, $this->getEntityClass());
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
    public function get(int $iId): AbstractEntity
    {
        $sQuery = '';
        $sQuery .= "select * from {$this->getTableName()} where id = $iId limit 1";

        return $this->getRelationshipService()->getEntity($sQuery, $this->getEntityClass());
    }

    /**
     * Check if the given column is including in database create / update query.
     *
     * @param \ReflectionProperty $oColumnProperty column to check.
     * @param bool $bInsertMode true for insert mode, false for update mode.
     *
     * @return bool true if column is enabled in given mode.
     */
    private function isColumnEnabled(\ReflectionProperty $oColumnProperty, bool $bInsertMode): bool
    {
        $bEnabled = true;
        if ($bInsertMode && 'id' === $oColumnProperty->getName()) {
            $bEnabled = false;
        } else {
            try {
                $oAnnotationReader = new AnnotationReader();
                if (null !== $oAnnotationReader->getPropertyAnnotation($oColumnProperty, Exclude::class)) {
                    $bEnabled = false;
                }
            } catch (AnnotationException $e) {
                $bEnabled = true;
            }
        }

        return $bEnabled;
    }

    /**
     * Get entity columns.
     *
     * @param AbstractEntity $oEntity entity.
     *
     * @return string[] columns with values.
     */
    private function getEntityColumns(AbstractEntity $oEntity): array
    {
        $aColumns = [];
        try {
            $oReflection = new \ReflectionClass($oEntity);
            foreach ($oReflection->getProperties() as $oColumnProperty) {
                if ($this->isColumnEnabled($oColumnProperty, true)) {
                    $oColumnProperty->setAccessible(true);
                    $mValue = $oColumnProperty->getValue($oEntity);
                    $sColumnName = $oColumnProperty->getName();
                    if (is_bool($mValue)) {
                        $sSqlValue = ($mValue ? '1' : '0');
                    } elseif ($mValue instanceof \DateTime) {
                        $sSqlValue = $this->getConnector()->escape($mValue->format('Y-m-d H:i:s'));
                    } elseif (is_int($mValue) || is_float($mValue)) {
                        $sSqlValue = $mValue;
                    } elseif ($mValue instanceof AbstractEntity) {
                        $sColumnName = $oColumnProperty->getName() . AbstractEntity::FOREIGN_KEY_SUFFIX;
                        $sSqlValue = $mValue->getId();
                    } else {
                        $sSqlValue = $this->getConnector()->escape($mValue);
                    }

                    if (null !== $sSqlValue) {
                        $aColumns [$sColumnName] = $sSqlValue;
                    }
                }
            }
        } catch (\ReflectionException $e) {
            $aColumns = [];
        }

        return $aColumns;
    }

    /**
     * Insert an entity into table.
     *
     * @param AbstractEntity $oEntity entity to insert.
     * @return AbstractEntity|null updated entity if success, otherwise null.
     */
    public function insert(AbstractEntity $oEntity): ?AbstractEntity
    {
        $aColumns = $this->getEntityColumns($oEntity);

        $sQuery = '';
        $sQuery .= ' INSERT INTO ' . $this->getTableName() . ' (';
        $sQuery .= implode(', ', array_keys($aColumns));
        $sQuery .= ' )';
        $sQuery .= ' VALUES (';
        $sQuery .= implode(',', array_values($aColumns));
        $sQuery .= ' );';

        try {
            if ($this->getConnector()->execute($sQuery)) {
                $oEntity->setId($this->getConnector()->getLastInsertId());
            } else {
                $oEntity = null;
            }
        } catch (QueryException $oException) {
            $this->getLogger()->error("Cannot create entity " . get_class($oEntity) . ' : ' . $oException->getMessage());
            $oEntity = null;
        }

        return $oEntity;
    }
}

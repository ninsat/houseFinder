<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\DatabaseCreation;

use Psr\Log\LoggerInterface;
use YoannBlot\Framework\Model\DataBase\TableStructure;
use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Exception\DataBaseException;
use YoannBlot\Framework\Model\Exception\EntityNotFoundException;
use YoannBlot\Framework\Model\Exception\QueryException;
use YoannBlot\Framework\Service\DatabaseConnector\ConnectorInterface;
use YoannBlot\Framework\Service\DatabaseConnector\ConnectorTrait;
use YoannBlot\Framework\Service\Logger\LoggerTrait;
use YoannBlot\Framework\Service\Repository\FactoryService;
use YoannBlot\Framework\Service\Repository\RepositoryFactoryTrait;

/**
 * Class RelationshipService.
 *
 * @package YoannBlot\Framework\Service\DatabaseCreation
 */
class RelationshipService
{

    use ConnectorTrait, LoggerTrait, RepositoryFactoryTrait, StructureTrait;

    /**
     * RelationshipService constructor.
     *
     * @param ConnectorInterface $oConnectorService connector.
     * @param LoggerInterface $oLoggerService logger.
     * @param FactoryService $oRepositoryFactoryService repository factory service.
     * @param StructureService $oStructureService structure service.
     */
    public function __construct(
        ConnectorInterface $oConnectorService,
        LoggerInterface $oLoggerService,
        FactoryService $oRepositoryFactoryService,
        StructureService $oStructureService
    ) {
        $this->oConnector = $oConnectorService;
        $this->oLogger = $oLoggerService;
        $this->oRepositoryFactoryService = $oRepositoryFactoryService;
        $this->oStructureService = $oStructureService;
    }

    /**
     * Get right entity.
     *
     * @param string $sQuery query to execute.
     * @param string $sEntityClass entity class to load.
     *
     * @return AbstractEntity entity.
     *
     * @throws EntityNotFoundException entity not found.
     * @throws QueryException error in SQL query.
     */
    public function getEntity(string $sQuery, string $sEntityClass): AbstractEntity
    {
        $oEntity = $this->getConnector()->querySingle($sQuery, $sEntityClass);
        $this->load($oEntity);

        return $oEntity;
    }

    /**
     * Load all entity links.
     *
     * @param AbstractEntity $oEntity entity.
     */
    private function load(AbstractEntity $oEntity): void
    {
        $this->setManyToOneAssociations($oEntity);
        $this->setOneToManyAssociations($oEntity);
    }

    /**
     * Add ManyToOne associations to given entity.
     *
     * @param AbstractEntity $oEntity entity.
     */
    private function setManyToOneAssociations(AbstractEntity $oEntity): void
    {
        try {
            $oReflection = new \ReflectionClass(AbstractEntity::class);
            $oProperty = $oReflection->getProperty('aForeignKeyValues');
            $oProperty->setAccessible(true);
            $aForeignValues = $oProperty->getValue($oEntity);
            foreach ($aForeignValues as $sColumnName => $iForeignKeyValue) {
                $oColumn = new \ReflectionProperty($oEntity, $sColumnName);

                if (null !== $iForeignKeyValue && 0 !== $iForeignKeyValue) {
                    $sForeignClass = substr($oColumn->getDocComment(),
                        strpos($oColumn->getDocComment(), '@var ') + strlen('@var '));
                    $sForeignClass = substr($sForeignClass, 0, strpos($sForeignClass, ' '));

                    $oForeignRepository = $this->getRepositoryFactoryService()->getRepository($sForeignClass);
                    if (null !== $oForeignRepository) {
                        $oForeignEntity = $oForeignRepository->get($iForeignKeyValue);
                        $oColumn->setAccessible(true);
                        $oColumn->setValue($oEntity, $oForeignEntity);
                    }
                }
            }
        } catch (\ReflectionException $e) {
        } catch (DataBaseException $e) {
        }
    }

    /**
     * Load and set many to many associations.
     *
     * @param AbstractEntity $oEntity entity.
     */
    private function setOneToManyAssociations(AbstractEntity $oEntity): void
    {
        $oStructureTable = $this->getTable($oEntity);
        foreach ($oStructureTable->getManyToManyColumns() as $oManyToManyColumn) {
            $oForeignRepository = $this->getRepositoryFactoryService()->getRepository($oManyToManyColumn->getType());
            $oForeignTable = $this->getStructureService()->getTable($oForeignRepository);

            $sManyToManyTable = $oStructureTable->getName() . '_' . $oForeignTable->getName();

            $sQuery = '';
            $sQuery .= " SELECT f.*";
            $sQuery .= " FROM {$oForeignRepository->getTableName()} f";
            $sQuery .= " INNER JOIN $sManyToManyTable l ON f.id = l.{$oForeignTable->getName()}_id AND l.{$oStructureTable->getName()}_id = :id";

            $this->getConnector()->setParameters([':id' => $oEntity->getId()]);
            $aValues = $this->getEntities($sQuery, $oManyToManyColumn->getType());

            try {
                $oProperty = new \ReflectionProperty($oEntity, $oManyToManyColumn->getName());
                $oProperty->setAccessible(true);
                $oProperty->setValue($oEntity, $aValues);
            } catch (\ReflectionException $e) {
                $this->getLogger()->error("Cannot load property {$oManyToManyColumn->getName()} in entity " . get_class($oEntity));
            }
        }
    }

    /**
     * Get structure table from given entity.
     *
     * @param AbstractEntity $oEntity entity.
     *
     * @return TableStructure matched structure table.
     */
    private function getTable(AbstractEntity $oEntity): TableStructure
    {
        return $this->getStructureService()->getTable($this->getRepositoryFactoryService()->getRepository(get_class($oEntity)));
    }

    /**
     * Get all entities matched by given query.
     *
     * @param string $sQuery SQL query to execute.
     * @param string $sEntityClass entity class to load.
     *
     * @return AbstractEntity[] entities.
     */
    public function getEntities(string $sQuery, string $sEntityClass): array
    {
        try {
            $aEntities = [];
            foreach ($this->getConnector()->queryMultiple($sQuery, $sEntityClass) as $oEntity) {
                $this->load($oEntity);
                $aEntities [] = $oEntity;
            }
        } catch (QueryException $oException) {
            $this->getLogger()->error("Error retrieving entities : " . $oException->getMessage() . '. ' . $sQuery);
            $aEntities = [];
        }

        return $aEntities;
    }

}
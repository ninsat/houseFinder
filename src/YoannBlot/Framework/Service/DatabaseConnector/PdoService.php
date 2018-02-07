<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\DatabaseConnector;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use YoannBlot\Framework\Model\DataBase\Annotation\ManyToMany;
use YoannBlot\Framework\Model\DataBase\ConfigurationConstants;
use YoannBlot\Framework\Model\DataBase\DataBaseConfig;
use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Exception\DataBaseException;
use YoannBlot\Framework\Model\Exception\EntityNotFoundException;
use YoannBlot\Framework\Model\Exception\QueryException;
use YoannBlot\Framework\Service\ConfigurationLoader\LoaderInterface;
use YoannBlot\Framework\Service\Repository\FactoryService;
use YoannBlot\Framework\Service\Repository\RepositoryFactoryTrait;

/**
 * Class PdoService.
 *
 * @package YoannBlot\Framework\Service\DatabaseConnector
 */
class PdoService implements ConnectorInterface
{

    use RepositoryFactoryTrait;

    /**
     * @var \PDO database connection object.
     */
    private $oConnection = null;

    /**
     * @var LoaderInterface configuration loader service.
     */
    private $oLoaderService = null;

    /**
     * @var DataBaseConfig database configuration.
     */
    private $oConfiguration = null;

    /**
     * @var array query parameters.
     */
    private $aQueryParameters = [];


    /**
     * PdoService constructor.
     *
     * @param LoaderInterface $oLoaderService loader.
     * @param FactoryService $oRepositoryService repository factory service.
     */
    public function __construct(LoaderInterface $oLoaderService, FactoryService $oRepositoryService)
    {
        $this->oLoaderService = $oLoaderService;
        $this->oRepositoryFactoryService = $oRepositoryService;
        $this->initConnection();
    }

    /**
     * @return LoaderInterface loader service.
     */
    private function getLoader(): LoaderInterface
    {
        return $this->oLoaderService;
    }

    /**
     * @inheritdoc
     */
    public function getConfiguration(): DataBaseConfig
    {
        return $this->oConfiguration;
    }

    /**
     * @return array query parameters.
     */
    private function getParameters(): array
    {
        return $this->aQueryParameters;
    }

    /**
     * @inheritdoc
     */
    public function setParameters(array $aParameters): void
    {
        $this->aQueryParameters = $aParameters;
    }

    /**
     * Open a connection to database.
     */
    private function initConnection()
    {
        if (!$this->isConnected()) {
            $this->getLoader()->load(ConfigurationConstants::PATH);
            $this->oConfiguration = new DataBaseConfig();
            $this->getConfiguration()->setHost($this->getLoader()->get(ConfigurationConstants::HOST,
                ConfigurationConstants::SECTION));
            $this->getConfiguration()->setPort(intval($this->getLoader()->get(ConfigurationConstants::PORT,
                ConfigurationConstants::SECTION)));
            $this->getConfiguration()->setUsername($this->getLoader()->get(ConfigurationConstants::USER,
                ConfigurationConstants::SECTION));
            $this->getConfiguration()->setPassword($this->getLoader()->get(ConfigurationConstants::PASSWORD,
                ConfigurationConstants::SECTION));
            $this->getConfiguration()->setDatabaseName($this->getLoader()->get(ConfigurationConstants::DATABASE_NAME,
                ConfigurationConstants::SECTION));

            $sConnectionSettings = 'mysql:host=' . $this->getConfiguration()->getHost() . ':' . $this->getConfiguration()->getPort() . ';dbname=' . $this->getConfiguration()->getDatabaseName();
            $this->oConnection = new \PDO($sConnectionSettings, $this->getConfiguration()->getUsername(),
                $this->getConfiguration()->getPassword());
            $this->oConnection->exec("SET CHARACTER SET utf8");
        }
    }

    /**
     * @return \PDO database connection object.
     */
    private function getConnection(): \PDO
    {
        return $this->oConnection;
    }

    /**
     * @return bool true if connected to database, otherwise false.
     */
    private function isConnected(): bool
    {
        return null !== $this->oConnection && $this->oConnection instanceof \PDO;
    }

    /**
     * Close the database connection.
     */
    public function close()
    {
        if ($this->isConnected()) {
            $this->oConnection = null;
        }
    }

    /**
     * @inheritDoc
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * @inheritdoc
     */
    public function fetchAll(string $sQuery): array
    {
        $this->initConnection();
        $oStatement = $this->getConnection()->prepare($sQuery);
        if (false === $oStatement->execute()) {
            throw new QueryException($sQuery, $oStatement->errorInfo()[2], intval($oStatement->errorCode()));
        }

        return $oStatement->fetchAll();
    }

    /**
     * @inheritdoc
     */
    public function execute(string $sQuery): bool
    {
        $this->initConnection();
        $bSuccess = false !== $this->getConnection()->exec($sQuery);

        if (!$bSuccess) {
            throw new QueryException($sQuery, $this->getConnection()->errorInfo()[2],
                intval($this->getConnection()->errorCode()));
        }

        return $bSuccess;
    }

    /**
     * @inheritdoc
     */
    public function querySingle(string $sQuery, string $sClassName): AbstractEntity
    {
        $this->initConnection();
        $oStatement = $this->getConnection()->prepare($sQuery);
        if (false === $oStatement->execute($this->getParameters())) {
            throw new QueryException($sQuery, $oStatement->errorInfo()[2], intval($oStatement->errorCode()));
        }
        $oObject = $oStatement->fetchObject($sClassName);
        if (false === $oObject) {
            throw new EntityNotFoundException();
        }
        $this->setManyToOneAssociations($oObject);
        $this->setOneToManyAssociations($oObject);

        return $oObject;
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
     * Check if given property is a table link or not.
     *
     * @param \ReflectionProperty $oProperty property.
     *
     * @return ManyToMany|null many to many column.
     */
    private function getManyToMany(\ReflectionProperty $oProperty): ?ManyToMany
    {
        try {
            $oAnnotationReader = new AnnotationReader();
            $oManyToMany = $oAnnotationReader->getPropertyAnnotation($oProperty, ManyToMany::class);
        } catch (AnnotationException $e) {
            $oManyToMany = null;
        }

        return $oManyToMany;
    }

    /**
     * Load and set many to many associations.
     *
     * @param AbstractEntity $oEntity entity.
     */
    private function setOneToManyAssociations(AbstractEntity $oEntity): void
    {
        try {
            $oReflection = new \ReflectionClass($oEntity);
            foreach ($oReflection->getProperties() as $oProperty) {
                $oManyToManyColumn = $this->getManyToMany($oProperty);
                if (null !== $oManyToManyColumn) {
                    $sForeignClass = substr($oProperty->getDocComment(),
                        strpos($oProperty->getDocComment(), '@var ') + strlen('@var '));
                    $sForeignClass = substr($sForeignClass, 0, strpos($sForeignClass, ' '));
                    $iBracketPosition = strpos($sForeignClass, '[]');
                    if (false !== $iBracketPosition) {
                        $sForeignClass = substr($sForeignClass, 0, $iBracketPosition);
                    }
                    $oForeignRepository = $this->getRepositoryFactoryService()->getRepository($sForeignClass);

                    $sQuery = '';
                    $sQuery .= " SELECT f.*";
                    $sQuery .= " FROM {$oForeignRepository->getTable()} f";
                    $sQuery .= " INNER JOIN {$oManyToManyColumn->table} l ON f.id = l.{$oManyToManyColumn->foreign_id} AND l.{$oManyToManyColumn->current_id} = :id";

                    $this->setParameters([':id' => $oEntity->getId()]);
                    $aValues = $this->queryMultiple($sQuery, $sForeignClass);

                    $oProperty->setAccessible(true);
                    $oProperty->setValue($oEntity, $aValues);
                }
            }
        } catch (\ReflectionException $e) {
        } catch (DataBaseException $e) {
        }
    }

    /**
     * @inheritdoc
     */
    public function queryMultiple(string $sQuery, string $sClassName): array
    {
        $this->initConnection();
        $oStatement = $this->getConnection()->prepare($sQuery);
        if (false === $oStatement->execute($this->getParameters())) {
            throw new QueryException($sQuery, $oStatement->errorInfo()[2], intval($oStatement->errorCode()));
        }
        $aObjects = [];
        foreach ($oStatement->fetchAll(\PDO::FETCH_CLASS, $sClassName) as $oObject) {
            $this->setManyToOneAssociations($oObject);
            $this->setOneToManyAssociations($oObject);
            $aObjects [] = $oObject;
        }

        return $aObjects;
    }

    /**
     * @inheritdoc
     */
    public function getLastInsertId(): int
    {
        return intval($this->getConnection()->lastInsertId());
    }

    /**
     * @inheritdoc
     */
    public function escape(string $sQuery): string
    {
        return $this->getConnection()->quote($sQuery);
    }

}

<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Command\Database;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use YoannBlot\Framework\Command\AbstractCommand;
use YoannBlot\Framework\Model\DataBase\Annotation\AutoIncrement;
use YoannBlot\Framework\Model\DataBase\Annotation\Length;
use YoannBlot\Framework\Model\DataBase\Annotation\ManyToMany;
use YoannBlot\Framework\Model\DataBase\Annotation\Nullable;
use YoannBlot\Framework\Model\DataBase\Annotation\PrimaryKey;
use YoannBlot\Framework\Model\DataBase\TableColumn;
use YoannBlot\Framework\Model\Exception\QueryException;
use YoannBlot\Framework\Model\Repository\AbstractRepository;
use YoannBlot\Framework\Service\DatabaseConnector\ConnectorInterface;
use YoannBlot\Framework\Service\DatabaseConnector\ConnectorTrait;
use YoannBlot\Framework\Service\Logger\LoggerService;

/**
 * Class Database\StructureUpdateCommand.
 *
 * @package YoannBlot\Framework\Command\Database
 */
class StructureUpdateCommand extends AbstractCommand
{

    use ConnectorTrait;

    /**
     * @var AbstractRepository[] repositories.
     */
    private $aRepositories = [];

    /**
     * @var AnnotationReader annotation reader.
     */
    private $oAnnotationReader = null;

    /**
     * AbstractCommand constructor.
     *
     * @param LoggerService $oLogger logger.
     * @param ConnectorInterface $oConnectorService connector service.
     * @param AbstractRepository[] $repositoryServicesList all repositories.
     */
    public function __construct(
        LoggerService $oLogger,
        ConnectorInterface $oConnectorService,
        array $repositoryServicesList
    )
    {
        parent::__construct($oLogger);
        $this->oConnector = $oConnectorService;
        $this->aRepositories = $repositoryServicesList;
    }

    /**
     * Get all repositories.
     *
     * @return AbstractRepository[] project repositories.
     */
    private function getAllRepositories(): array
    {
        return $this->aRepositories;
    }

    /**
     * @return AnnotationReader annotation reader.
     */
    private function getAnnotationReader(): AnnotationReader
    {
        if (null === $this->oAnnotationReader) {
            try {
                $this->oAnnotationReader = new AnnotationReader();
            } catch (AnnotationException $oException) {
                $this->getLogger()->error("Cannot create annotation reader...");
            }
        }
        return $this->oAnnotationReader;
    }

    /**
     * @inheritdoc
     */
    public function run(): bool
    {
        $bSuccess = true;
        foreach ($this->getAllRepositories() as $oRepository) {
            $this->getLogger()->info("Repository " . get_class($oRepository) . " => table '{$oRepository->getTable()}'.");
            if (!$this->tableExists($oRepository->getTable()) && !$this->createTable($oRepository)) {
                $this->getLogger()->error("Error creating table '{$oRepository->getTable()}'.");
                $bSuccess = false;
            }
        }

        $this->createManyToManyTables();

        return $bSuccess;
    }

    /**
     * Check if given table name already exists in database.
     *
     * @param string $sTableName table name.
     * @return bool true if table already exists.
     */
    private function tableExists(string $sTableName): bool
    {
        $sDatabaseName = $this->getConnector()->getConfiguration()->getDatabaseName();

        $sQuery = '';
        $sQuery .= 'SELECT table_name ';
        $sQuery .= 'FROM information_schema.tables ';
        $sQuery .= "WHERE table_schema = '$sDatabaseName' ";
        $sQuery .= "AND table_name = '$sTableName' ";
        $sQuery .= "LIMIT 1 ";

        try {
            $bExists = count($this->getConnector()->fetchAll($sQuery)) > 0;
        } catch (QueryException $oException) {
            $this->getLogger()->error($oException->getMessage());
            $bExists = false;
        }

        return $bExists;
    }

    /**
     * Create many to many tables.
     */
    private function createManyToManyTables(): void
    {
        foreach ($this->getAllRepositories() as $oRepository) {
            try {
                $oReflection = new \ReflectionClass($oRepository->getEntityClass());
                foreach ($oReflection->getProperties() as $oProperty) {
                    if ($this->isManyToMany($oProperty)) {
                        /** @var ManyToMany $oAnnotation */
                        $oAnnotation = $this->getAnnotationReader()->getPropertyAnnotation($oProperty,
                            ManyToMany::class);
                        if (!$this->tableExists($oAnnotation->table)) {
                            $this->createManyToManyTable($oRepository, $this->getForeignRepository($oProperty),
                                $oAnnotation);
                        }
                    }
                }
            } catch (\ReflectionException $oException) {
                $this->getLogger()->error("Cannot create entity from class '{$oRepository->getEntityClass()}' : " . $oException->getMessage());
            }
        }
    }

    /**
     * Get the matched Foreign repository of a ManyToMany column.
     *
     * @param \ReflectionProperty $oProperty property where find repository.
     *
     * @return AbstractRepository matched repository.
     */
    private function getForeignRepository(\ReflectionProperty $oProperty): AbstractRepository
    {
        $oFoundRepository = null;
        $sEntityType = $this->getVariableType($oProperty);
        $iBracketPosition = strpos($sEntityType, '[]');
        if (false !== $iBracketPosition) {
            $sEntityType = substr($sEntityType, 0, $iBracketPosition);
            foreach ($this->getAllRepositories() as $oRepository) {
                if (false !== strpos($sEntityType, $oRepository->getEntityClass())) {
                    $oFoundRepository = $oRepository;
                    break;
                }
            }
        }

        return $oFoundRepository;
    }

    /**
     * Get the table SQL structure.
     *
     * @param AbstractRepository $oRepository repository.
     * @return bool true if table already exists.
     */
    private function createTable(AbstractRepository $oRepository): bool
    {
        $sDatabaseName = $this->getConnector()->getConfiguration()->getDatabaseName();

        $sQuery = '';
        $sQuery .= "CREATE TABLE $sDatabaseName.{$oRepository->getTable()} ( ";
        $aColumnsQuery = [];
        $aIdFields = [];
        foreach ($this->getColumns($oRepository) as $oColumn) {
            if ($oColumn->isPrimaryKey()) {
                $aIdFields[] = $oColumn->getName();
            }
            $sColumnQuery = " {$oColumn->getName()} {$oColumn->getType()} ";
            $sColumnQuery .= (!$oColumn->isNullable() ? 'NOT ' : '') . 'NULL';
            if ($oColumn->hasDefaultValue()) {
                $sColumnQuery .= ' DEFAULT ' . $oColumn->getDefaultValue();
            }
            if ($oColumn->isAutoIncrement()) {
                $sColumnQuery .= ' AUTO_INCREMENT';
            }
            $aColumnsQuery[] = $sColumnQuery;
        }
        $sQuery .= implode(',', $aColumnsQuery);
        if (count($aIdFields) > 0) {
            $sQuery .= "  ,PRIMARY KEY (" . implode(',', $aIdFields) . ') ';
        }
        $sQuery .= " ) ";
        $sQuery .= "ENGINE = InnoDB ";
        $sQuery .= "DEFAULT CHARACTER SET = utf8;";

        $bSuccess = true;
        try {
            $this->getConnector()->execute($sQuery);
        } catch (QueryException $oException) {
            $this->getLogger()->error($oException->getMessage());
            $bSuccess = false;
        }

        return $bSuccess;
    }

    /**
     * Get all columns in given repository.
     *
     * @param AbstractRepository $oRepository repository.
     *
     * @return TableColumn[] columns.
     */
    private function getColumns(AbstractRepository $oRepository): array
    {
        $aColumns = [];
        $sEntityClass = $oRepository->getEntityClass();
        try {
            $oReflection = new \ReflectionClass($sEntityClass);
            foreach ($oReflection->getProperties() as $oProperty) {
                if (!$this->isManyToMany($oProperty)) {
                    $aColumns [] = $this->getColumn($oProperty);
                }
            }
        } catch (\ReflectionException $oException) {
            $this->getLogger()->error("Cannot load entity class '$sEntityClass'. " . $oException->getMessage());
        }

        return $aColumns;
    }

    /**
     * Check if given property is a table link or not.
     *
     * @param \ReflectionProperty $oProperty property.
     *
     * @return bool true if given property is a table link.
     */
    private function isManyToMany(\ReflectionProperty $oProperty): bool
    {
        return null !== $this->getAnnotationReader()->getPropertyAnnotation($oProperty, ManyToMany::class);
    }

    /**
     * Get the right variable type.
     *
     * @param \ReflectionProperty $oProperty property to retrieve variable.
     *
     * @return string variable type.
     */
    private function getVariableType(\ReflectionProperty $oProperty): string
    {
        $sVariableType = substr($oProperty->getDocComment(),
            strpos($oProperty->getDocComment(), '@var ') + strlen('@var '));
        return substr($sVariableType, 0, strpos($sVariableType, ' '));
    }

    /**
     * Get the SQL column type from property.
     *
     * @param \ReflectionProperty $oProperty
     * @return string SQL column type.
     */
    private function getSqlType(\ReflectionProperty $oProperty): string
    {
        // get length
        $iLength = null;
        /** @var Length $oLengthAnnotation */
        $oLengthAnnotation = $this->getAnnotationReader()->getPropertyAnnotation($oProperty, Length::class);
        if (null !== $oLengthAnnotation) {
            $iLength = $oLengthAnnotation->getLength();
        }

        // get SQL type
        switch ($this->getVariableType($oProperty)) {
            case 'bool':
            case 'boolean':
                $sSqlType = 'TINYINT(1) UNSIGNED';
                break;
            case 'int':
                if (null === $iLength) {
                    $iLength = 11;
                }
                // TODO switch ($iLength ) : tinyint, smallint, mediumint, int, long
                $sSqlType = "INT($iLength) UNSIGNED";
                break;
            case 'float':
                // TODO decimal, float or double
                $sSqlType = "DOUBLE";
                break;
            // TODO other types
            default:
                if (null === $iLength) {
                    $iLength = 500;
                }
                $sSqlType = "VARCHAR($iLength)";
                break;
        }

        return $sSqlType;
    }

    /**
     * Get column from reflection property.
     *
     * @param \ReflectionProperty $oProperty property.
     *
     * @return TableColumn column.
     */
    private function getColumn(\ReflectionProperty $oProperty): TableColumn
    {
        $sSqlType = $this->getSqlType($oProperty);

        // check if nullable
        /** @var Nullable $oNullableAnnotation */
        $oNullableAnnotation = $this->getAnnotationReader()->getPropertyAnnotation($oProperty, Nullable::class);
        $bNullable = (null === $oNullableAnnotation) || $oNullableAnnotation->isNullable();

        $bAutoIncrement = null !== $this->getAnnotationReader()->getPropertyAnnotation($oProperty,
                AutoIncrement::class);

        $bPrimary = null !== $this->getAnnotationReader()->getPropertyAnnotation($oProperty, PrimaryKey::class);

        // TODO default value
        $sDefaultValue = null;

        return new TableColumn(
            $oProperty->getName(),
            $sSqlType,
            $bNullable,
            $sDefaultValue,
            $bAutoIncrement,
            $bPrimary
        );
    }

    /**
     * Create a Many to Many table.
     *
     * @param AbstractRepository $oCurrentRepository current repository instance.
     * @param AbstractRepository $oForeignRepository foreign repository instance.
     * @param ManyToMany $oAnnotation annotation.
     * @return bool true if success, otherwise false.
     */
    private function createManyToManyTable(
        AbstractRepository $oCurrentRepository,
        AbstractRepository $oForeignRepository,
        ManyToMany $oAnnotation
    ): bool {
        $bSuccess = false;
        try {
            $oCurrentReflection = new \ReflectionClass($oCurrentRepository->getEntityClass());
            $oForeignReflection = new \ReflectionClass($oForeignRepository->getEntityClass());

            $sDatabaseName = $this->getConnector()->getConfiguration()->getDatabaseName();

            $sCurrentIdType = $this->getSqlType($oCurrentReflection->getProperty('id'));
            $sForeignIdType = $this->getSqlType($oForeignReflection->getProperty('id'));

            $sQuery = '';
            $sQuery .= "CREATE TABLE $sDatabaseName.{$oAnnotation->table} ( ";
            $sQuery .= "  {$oAnnotation->current_id} $sCurrentIdType,";
            $sQuery .= "  {$oAnnotation->foreign_id} $sForeignIdType,";
            $sQuery .= "  PRIMARY KEY ({$oAnnotation->current_id}, {$oAnnotation->foreign_id}),";
            // Foreign key on Current Entity
            $sQuery .= "  FOREIGN KEY ({$oAnnotation->current_id})";
            $sQuery .= "  REFERENCES $sDatabaseName.{$oCurrentRepository->getTable()} (id)";
            $sQuery .= "  ON DELETE CASCADE";
            $sQuery .= "  ON UPDATE CASCADE,";
            // Foreign key on Foreign Entity
            $sQuery .= "  FOREIGN KEY ({$oAnnotation->foreign_id})";
            $sQuery .= "  REFERENCES $sDatabaseName.{$oForeignRepository->getTable()} (id)";
            $sQuery .= "  ON DELETE CASCADE";
            $sQuery .= "  ON UPDATE CASCADE";
            $sQuery .= " ) ";
            $sQuery .= "ENGINE = InnoDB ";
            $sQuery .= "DEFAULT CHARACTER SET = utf8;";

            $this->getConnector()->execute($sQuery);
            $bSuccess = true;
        } catch (\ReflectionException $oException) {
            $this->getLogger()->error("Cannot create reflection class : " . $oException->getMessage());
        } catch (QueryException $oException) {
            $this->getLogger()->error("QueryException : " . $oException->getMessage());
        }

        return $bSuccess;
    }
}
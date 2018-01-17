<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Command\Database;

use Doctrine\Common\Annotations\AnnotationReader;
use YoannBlot\Framework\Command\AbstractCommand;
use YoannBlot\Framework\Model\DataBase\Annotation\AutoIncrement;
use YoannBlot\Framework\Model\DataBase\Annotation\Length;
use YoannBlot\Framework\Model\DataBase\Annotation\Nullable;
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
     * AbstractCommand constructor.
     *
     * @param LoggerService $oLogger logger.
     * @param ConnectorInterface $oConnectorService connector service.
     * @param AbstractRepository[] $repositories all repositories.
     */
    public function __construct(LoggerService $oLogger, ConnectorInterface $oConnectorService, array $repositories)
    {
        parent::__construct($oLogger);
        $this->oConnector = $oConnectorService;
        $this->aRepositories = $repositories;
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

        return $bSuccess;
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
        foreach ($this->getColumns($oRepository) as $oColumn) {
            $sColumnQuery = " {$oColumn->getName()} {$oColumn->getType()} ";
            $sColumnQuery .= (!$oColumn->isNullable() ? 'NOT ' : '') . 'NULL';
            if ($oColumn->hasDefaultValue()) {
                $sColumnQuery .= ' DEFAULT ' . $oColumn->getDefaultValue();
            }
            $aColumnsQuery[] = $sColumnQuery;
        }
        $sQuery .= implode(',', $aColumnsQuery);
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
        $oReflection = new \ReflectionClass($sEntityClass);
        foreach ($oReflection->getProperties() as $oProperty) {
            $aColumns [] = $this->getColumn($oProperty);
        }

        return $aColumns;
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
        $oAnnotationReader = new AnnotationReader();

        $sVariableType = substr($oProperty->getDocComment(),
            strpos($oProperty->getDocComment(), '@var ') + strlen('@var '));
        $sVariableType = substr($sVariableType, 0, strpos($sVariableType, ' '));

        // get length
        $iLength = null;
        /** @var Length $oLengthAnnotation */
        $oLengthAnnotation = $oAnnotationReader->getPropertyAnnotation($oProperty, Length::class);
        if (null !== $oLengthAnnotation) {
            $iLength = $oLengthAnnotation->getLength();
        }

        // get SQL type
        switch ($sVariableType) {
            case 'bool':
            case 'boolean':
                $sSqlType = 'TINYINT(1) UNSIGNED';
                break;
            case 'int':
                if (null === $iLength) {
                    $iLength = 11;
                }
                $sSqlType = "INT($iLength) UNSIGNED";
                break;
            case 'float':
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

        // check if nullable
        /** @var Nullable $oNullableAnnotation */
        $oNullableAnnotation = $oAnnotationReader->getPropertyAnnotation($oProperty, Nullable::class);
        $bNullable = (null === $oNullableAnnotation) || $oNullableAnnotation->isNullable();

        $bAutoIncrement = null !== $oAnnotationReader->getPropertyAnnotation($oProperty, AutoIncrement::class);

        // TODO default value
        $sDefaultValue = null;

        $oColumn = new TableColumn($oProperty->getName(), $sSqlType, $bNullable, $sDefaultValue, $bAutoIncrement);

        return $oColumn;
    }
}
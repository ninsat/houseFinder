<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Command\Database;

use YoannBlot\Framework\Command\AbstractCommand;
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
     * AbstractCommand constructor.
     *
     * @param LoggerService $oLogger logger.
     * @param ConnectorInterface $oConnectorService connector service.
     */
    public function __construct(LoggerService $oLogger, ConnectorInterface $oConnectorService)
    {
        parent::__construct($oLogger);
        $this->oConnector = $oConnectorService;
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
        $aRepositories = [];

        $aFileRepositories = glob(SRC_PATH . 'YoannBlot/*/Model/Repository/*.php');
        foreach ($aFileRepositories as $sRepositoryPath) {
            if (false === strpos($sRepositoryPath, 'Abstract')) {
                $sRepositoryPath = str_replace([SRC_PATH, '.php'], '', $sRepositoryPath);
                $sRepositoryPath = str_replace('/', '\\', $sRepositoryPath);
                $oReflection = new \ReflectionClass($sRepositoryPath);

                $oRepository = $oReflection->newInstance();
                if ($oRepository instanceof AbstractRepository) {
                    $aRepositories[] = $oRepository;
                }
            }
        }

        return $aRepositories;
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


        return $this->getConnector()->execute($sQuery);
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
        $sVariableType = substr($oProperty->getDocComment(),
            strpos($oProperty->getDocComment(), '@var ') + strlen('@var '));
        $sVariableType = substr($sVariableType, 0, strpos($sVariableType, ' '));

        // get length
        $iLength = null;
        if (false !== strpos($oProperty->getDocComment(), TableColumn::LENGTH)) {
            $iLength = substr($oProperty->getDocComment(),
                strpos($oProperty->getDocComment(), TableColumn::LENGTH) + strlen(TableColumn::LENGTH));
            $iLength = substr($iLength, 0, strpos($iLength, ' '));
            $iLength = intval(trim($iLength));
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
            // TODO other types
            default:
                if (null === $iLength) {
                    $iLength = 500;
                }
                $sSqlType = "VARCHAR($iLength)";
                break;
        }

        // check if nullable
        $bNullable = true;
        if (false !== strpos($oProperty->getDocComment(), TableColumn::NULLABLE)) {
            $bNullable = substr($oProperty->getDocComment(),
                strpos($oProperty->getDocComment(), TableColumn::NULLABLE) + strlen(TableColumn::NULLABLE));
            $bNullable = substr($bNullable, 0, strpos($bNullable, ' '));
            $bNullable = ('true' === trim($bNullable));
        }

        // TODO default value

        return new TableColumn($oProperty->getName(), $sSqlType, $bNullable);
    }
}
<?php

namespace YoannBlot\Framework\Service\DatabaseCreation;

use YoannBlot\Framework\Model\DataBase\ForeignKeyColumn;
use YoannBlot\Framework\Model\DataBase\TableColumn;
use YoannBlot\Framework\Model\DataBase\TableStructure;
use YoannBlot\Framework\Model\Exception\QueryException;
use YoannBlot\Framework\Service\DatabaseConnector\ConnectorInterface;
use YoannBlot\Framework\Service\DatabaseConnector\ConnectorTrait;

/**
 * Class TableService.
 *
 * @package YoannBlot\Framework\Service\DatabaseCreation
 */
class TableService
{

    use ConnectorTrait;

    /**
     * TableService constructor.
     *
     * @param ConnectorInterface $oConnectorService
     */
    public function __construct(ConnectorInterface $oConnectorService)
    {
        $this->oConnector = $oConnectorService;
    }

    /**
     * Check if given table name already exists in database.
     *
     * @param string $sTableName table name.
     * @return bool true if table already exists.
     */
    public function exists(string $sTableName): bool
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
            $bExists = false;
        }

        return $bExists;
    }

    /**
     * Create the table.
     *
     * @param TableStructure $oTableStructure table structure.
     *
     * @return bool true if success, otherwise false.
     */
    public function create(TableStructure $oTableStructure): bool
    {
        $aColumnsQuery = [];
        foreach ($oTableStructure->getColumns() as $oColumn) {
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

        try {
            $sQuery = $this->generateCreateQuery($oTableStructure->getName(), $aColumnsQuery,
                $oTableStructure->getPrimaryKeys(), $oTableStructure->getForeignKeys());
            $bSuccess = $this->getConnector()->execute($sQuery);
        } catch (QueryException $oException) {
            $bSuccess = false;
        }

        return $bSuccess;
    }

    /**
     * Generate the SQL query for table creation.
     *
     * @param string $sTableName table name.
     * @param string[] $aColumnsQuery columns as SQL.
     * @param TableColumn[] $aPrimaryKeys primary keys.
     * @param ForeignKeyColumn[] $aForeignKeys foreign keys.
     *
     * @return string SQL query for table creation.
     */
    private function generateCreateQuery(
        string $sTableName,
        array $aColumnsQuery,
        array $aPrimaryKeys,
        array $aForeignKeys
    ): string {
        $sDatabaseName = $this->getConnector()->getConfiguration()->getDatabaseName();

        $sQuery = '';
        $sQuery .= "CREATE TABLE $sDatabaseName.$sTableName ( ";
        $sQuery .= implode(',', $aColumnsQuery);
        if (count($aPrimaryKeys) > 0) {
            $aPrimaryKeyNames = [];
            foreach ($aPrimaryKeys as $oPrimaryKeyName) {
                $aPrimaryKeyNames [] = $oPrimaryKeyName->getName();
            }
            $sQuery .= "  ,PRIMARY KEY (" . implode(',', $aPrimaryKeyNames) . ') ';
        }
        if (count($aForeignKeys) > 0) {
            foreach ($aForeignKeys as $oForeignKey) {
                $sQuery .= "  ,FOREIGN KEY ({$oForeignKey->getName()})";
                $sQuery .= "  REFERENCES $sDatabaseName.{$oForeignKey->getForeignTable()} ({$oForeignKey->getForeignPrimaryKey()->getName()})";
                $sQuery .= "  ON DELETE CASCADE";
                $sQuery .= "  ON UPDATE CASCADE";
            }
        }
        $sQuery .= " ) ";
        $sQuery .= "ENGINE = InnoDB ";
        $sQuery .= "DEFAULT CHARACTER SET = utf8;";

        return $sQuery;
    }
}
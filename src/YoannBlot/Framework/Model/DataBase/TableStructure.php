<?php

namespace YoannBlot\Framework\Model\DataBase;

/**
 * Class TableStructure.
 *
 * @package YoannBlot\Framework\Model\DataBase
 */
class TableStructure
{

    /**
     * @var string table name.
     */
    private $sName;

    /**
     * @var TableColumn[] columns.
     */
    private $aColumns = [];

    /**
     * TableStructure constructor.
     *
     * @param string $sName table name
     * @param array $aColumns table columns.
     */
    public function __construct(string $sName, array $aColumns = [])
    {
        $this->sName = $sName;
        $this->aColumns = $aColumns;
    }

    /**
     * @return string table name.
     */
    public function getName(): string
    {
        return $this->sName;
    }

    /**
     * @return TableColumn[] all SQL columns.
     */
    public function getColumns(): array
    {
        $aTableColumns = [];
        foreach ($this->aColumns as $oColumn) {
            if (!($oColumn instanceof ManyToManyColumn)) {
                $aTableColumns[] = $oColumn;
            }
        }

        return $aTableColumns;
    }

    /**
     * @return TableColumn[] primary key columns.
     */
    public function getPrimaryKeys(): array
    {
        $aPrimaryKeys = [];
        foreach ($this->aColumns as $oColumn) {
            if ($oColumn->isPrimaryKey()) {
                $aPrimaryKeys[] = $oColumn;
            }
        }

        return $aPrimaryKeys;
    }

    /**
     * @return ForeignKeyColumn[] primary key columns.
     */
    public function getForeignKeys(): array
    {
        $aForeignKeys = [];
        foreach ($this->aColumns as $oColumn) {
            if ($oColumn instanceof ForeignKeyColumn) {
                $aForeignKeys[] = $oColumn;
            }
        }

        return $aForeignKeys;
    }

    /**
     * @return ManyToManyColumn[] many to many columns.
     */
    public function getManyToManyColumns(): array
    {
        $aManyToManyColumns = [];
        foreach ($this->aColumns as $oColumn) {
            if ($oColumn instanceof ManyToManyColumn) {
                $aManyToManyColumns[] = $oColumn;
            }
        }

        return $aManyToManyColumns;
    }
}
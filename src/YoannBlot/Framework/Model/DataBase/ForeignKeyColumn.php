<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\DataBase;

use YoannBlot\Framework\Validator\Boolean;

/**
 * Class ForeignKeyColumn.
 *
 * @package YoannBlot\Framework\Model\DataBase
 */
class ForeignKeyColumn extends TableColumn
{
    /**
     * @var TableColumn foreign primary key.
     */
    private $oForeignPrimaryKey;

    /**
     * @var string foreign table name.
     */
    private $sForeignTableName;

    /**
     * ForeignKeyColumn constructor.
     *
     * @param string $sName column name.
     * @param string $sForeignTableName foreign table name.
     * @param TableColumn $oForeignPrimaryKey foreign primary key.
     */
    public function __construct(string $sName, string $sForeignTableName, TableColumn $oForeignPrimaryKey)
    {
        $this->oForeignPrimaryKey = $oForeignPrimaryKey;
        $this->sForeignTableName = $sForeignTableName;
        parent::__construct($sName, $oForeignPrimaryKey->getType());
    }

    /**
     * @param bool $bPrimary primary key.
     */
    public function setPrimaryKey(bool $bPrimary): void
    {
        $this->bPrimary = Boolean::getValue($bPrimary);
    }

    /**
     * @return string foreign table.
     */
    public function getForeignTable(): string
    {
        return $this->sForeignTableName;
    }

    /**
     * @return TableColumn foreign primary key.
     */
    public function getForeignPrimaryKey(): TableColumn
    {
        return $this->oForeignPrimaryKey;
    }
}
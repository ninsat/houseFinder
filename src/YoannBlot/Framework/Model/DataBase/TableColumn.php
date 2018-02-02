<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\DataBase;

/**
 * Class TableColumn.
 *
 * @package YoannBlot\Framework\Model\DataBase
 */
class TableColumn
{
    const NULLABLE = '@nullable=';
    const LENGTH = '@length=';
    const AUTO_INCREMENT = '@AutoIncrement';

    /**
     * @var string column name.
     */
    private $sName;

    /**
     * @var string column type.
     */
    private $sType;

    /**
     * @var bool true if column is nullable, otherwise false.
     */
    private $bIsNullable = true;

    /**
     * @var bool true if column is auto increment, otherwise false.
     */
    private $bAutoIncrement = false;

    /**
     * @var bool true if column is a primary key, otherwise false.
     */
    private $bPrimary = false;

    /**
     * @var string default column value.
     */
    private $sDefaultValue = null;

    /**
     * TableColumn constructor.
     *
     * @param string $sName column name.
     * @param string $sType column type.
     * @param bool $bIsNullable is nullable.
     * @param string $sDefaultValue default value.
     * @param bool $bAutoIncrement auto increment.
     * @param bool $bPrimary primary key.
     */
    public function __construct(
        string $sName,
        string $sType,
        bool $bIsNullable = false,
        string $sDefaultValue = null,
        bool $bAutoIncrement = false,
        bool $bPrimary = false
    ) {
        $this->sName = $sName;
        $this->sType = strtoupper($sType);
        $this->bIsNullable = $bIsNullable;
        $this->sDefaultValue = $sDefaultValue;
        $this->bAutoIncrement = $bAutoIncrement;
        $this->bPrimary = $bPrimary;
    }

    /**
     * @return string column name.
     */
    public function getName(): string
    {
        return $this->sName;
    }

    /**
     * @return string column type.
     */
    public function getType(): string
    {
        return $this->sType;
    }

    /**
     * @return bool nullable.
     */
    public function isNullable(): bool
    {
        return $this->bIsNullable;
    }

    /**
     * @return string default value.
     */
    public function getDefaultValue(): ?string
    {
        return $this->sDefaultValue;
    }

    /**
     * @return bool true if column has a default value.
     */
    public function hasDefaultValue(): bool
    {
        return null !== $this->sDefaultValue;
    }

    /**
     * @return bool true if auto increment is enabled.
     */
    public function isAutoIncrement(): bool
    {
        return $this->bAutoIncrement;
    }

    /**
     * @return bool true if it's a primary key.
     */
    public function isPrimaryKey(): bool
    {
        return $this->bPrimary;
    }

}
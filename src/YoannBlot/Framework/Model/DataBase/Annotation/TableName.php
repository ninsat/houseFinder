<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\DataBase\Annotation;

/**
 * Class TableName.
 * This annotation set table name to a Repository.
 *
 * @package YoannBlot\Framework\Model\DataBase\Annotation
 * @Annotation
 * @Target({"CLASS"})
 */
final class TableName
{
    /**
     * @var string table name
     */
    private $value;

    /**
     * TableName constructor.
     *
     * @param array $aValues values.
     */
    public function __construct(array $aValues)
    {
        $this->value = $aValues['value'];
    }

    /**
     * @return string table name.
     */
    public function getName(): string
    {
        return $this->value;
    }
}
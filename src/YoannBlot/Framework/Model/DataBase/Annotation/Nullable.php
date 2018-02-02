<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\DataBase\Annotation;

use YoannBlot\Framework\Validator\Boolean;

/**
 * Class Nullable.
 * This annotation allows a column to be nullable (or not).
 *
 * @package YoannBlot\Framework\Model\DataBase\Annotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Nullable
{
    /**
     * @var boolean
     */
    private $value;

    /**
     * Nullable constructor.
     *
     * @param array $aValues values.
     */
    public function __construct(array $aValues)
    {
        $this->value = Boolean::getValue($aValues['value']);
    }

    /**
     * @return bool true if nullable otherwise false.
     */
    public function isNullable(): bool
    {
        return $this->value;
    }
}
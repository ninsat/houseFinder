<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\DataBase\Annotation;

/**
 * Class DefaultValue.
 * This annotation set a column's default value.
 *
 * @package YoannBlot\Framework\Model\DataBase\Annotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class DefaultValue
{
    /**
     * @var string value.
     */
    private $value;

    /**
     * DefaultValue constructor.
     *
     * @param array $aValues values.
     */
    public function __construct(array $aValues)
    {
        $this->value = '' . $aValues['value'];
    }

    /**
     * @return string default value.
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
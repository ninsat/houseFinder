<?php

namespace YoannBlot\Framework\Model\DataBase\Annotation;

/**
 * Class Length.
 * This annotation lets change column length.
 *
 * @package YoannBlot\Framework\Model\DataBase\Annotation
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Length
{
    /**
     * @var int length
     */
    private $value;

    /**
     * Length constructor.
     *
     * @param array $aValues values.
     */
    public function __construct(array $aValues)
    {
        $this->value = intval($aValues['value']);
    }

    /**
     * @return int length.
     */
    public function getLength(): int
    {
        return $this->value;
    }
}
<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Entity;

use YoannBlot\Framework\Model\Entity\Common\IdPrimaryKey;

/**
 * Class AbstractEntity.
 * All entities should extend this class.
 *
 * @package YoannBlot\Framework\Model\Entity
 * @author  Yoann Blot
 */
abstract class AbstractEntity
{

    const DEFAULT_ID = -1;

    const FOREIGN_KEY_SUFFIX = '_id';

    use IdPrimaryKey;

    /**
     * @var array waiting links.
     */
    private $aForeignKeyValues = [];

    /**
     * @inheritdoc
     */
    public function __set($name, $value): void
    {
        $iIdPosition = strpos($name, static::FOREIGN_KEY_SUFFIX);
        if (!property_exists($this, $name) && false !== $iIdPosition) {
            $this->aForeignKeyValues [substr($name, 0, $iIdPosition)] = intval($value);
        }
    }
}
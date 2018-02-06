<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\AutoIncrement;
use YoannBlot\Framework\Model\DataBase\Annotation\PrimaryKey;

/**
 * Trait IdPrimaryKey.
 *
 * @package YoannBlot\Framework\Model\Entity\Common
 */
trait IdPrimaryKey
{

    /**
     * @var int id
     * @AutoIncrement()
     * @PrimaryKey()
     */
    protected $id = self::DEFAULT_ID;

    /**
     * @return int
     */
    public function getId(): int
    {
        return intval($this->id);
    }

    /**
     * @param int $iId
     */
    public function setId(int $iId): void
    {
        $this->id = $iId;
    }
}
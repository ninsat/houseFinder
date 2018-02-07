<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

/**
 * Trait Bath.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Bath
{

    /**
     * @var bool has bath.
     */
    private $has_bath = false;

    /**
     * @return boolean
     */
    public function hasBath(): bool
    {
        return boolval($this->has_bath);
    }

    /**
     * @param boolean $bHasBath
     */
    public function setBath(bool $bHasBath): void
    {
        $this->has_bath = $bHasBath;
    }
}
<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

/**
 * Trait Type.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Type {

    /**
     * @var string type.
     */
    private $type = '';

    /**
     * @return string
     */
    public function getType (): string {
        return $this->type;
    }

    /**
     * @param string $sType
     */
    public function setType (string $sType) {
        if (strlen($sType) > 2) {
            $this->type = $sType;
        }
    }
}
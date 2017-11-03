<?php

namespace YoannBlot\HouseFinder\Model\Entity\Common;

/**
 * Trait Guarantee.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Guarantee {

    /**
     * @var float guarantee.
     */
    private $guarantee = 0;

    /**
     * @return float
     */
    public function getGuarantee (): ?float {
        return $this->guarantee;
    }

    /**
     * @param float $fGuarantee
     */
    public function setGuarantee (float $fGuarantee) {
        if ($fGuarantee >= 0 && $fGuarantee < 2000) {
            $this->guarantee = $fGuarantee;
        }
    }
}
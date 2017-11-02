<?php

namespace YoannBlot\HouseFinder\Model\Entity\Common;

/**
 * Trait Fees.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Fees {

    /**
     * @var float fees.
     */
    private $fees = 0;

    /**
     * @return float
     */
    public function getFees (): float {
        return $this->fees;
    }

    /**
     * @param float $fFees
     */
    public function setFees (float $fFees) {
        if ($fFees >= 0 && $fFees < 2000) {
            $this->fees = $fFees;
        }
    }
}
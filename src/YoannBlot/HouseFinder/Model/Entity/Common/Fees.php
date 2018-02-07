<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

/**
 * Trait Fees.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Fees
{

    /**
     * @var float fees.
     */
    private $fees = 0;

    /**
     * @return float
     */
    public function getFees(): ?float
    {
        return floatval($this->fees);
    }

    /**
     * @param float $fFees
     */
    public function setFees(float $fFees): void
    {
        if ($fFees < 0 || $fFees > 10000) {
            $fFees = 0;
        }
        $this->fees = $fFees;
    }
}
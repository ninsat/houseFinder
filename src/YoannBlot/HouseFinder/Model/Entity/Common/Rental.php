<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

/**
 * Trait Rental.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Rental
{

    /**
     * @var bool is rental.
     */
    private $is_rental = false;

    /**
     * @return boolean
     */
    public function isRental(): bool
    {
        return boolval($this->is_rental);
    }

    /**
     * @param boolean $bIsRental
     */
    public function setRental(bool $bIsRental): void
    {
        $this->is_rental = $bIsRental;
    }
}
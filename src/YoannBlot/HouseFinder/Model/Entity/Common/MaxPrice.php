<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

/**
 * Trait MaxPrice.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait MaxPrice
{

    /**
     * @var int maxPrice.
     */
    private $maxPrice = 0;

    /**
     * @return int
     */
    public function getMaxPrice(): int
    {
        return intval($this->maxPrice);
    }

    /**
     * @param int $fMaxPrice
     */
    public function setMaxPrice(int $fMaxPrice): void
    {
        if ($fMaxPrice < 0 || $fMaxPrice > 5000000) {
            $fMaxPrice = 0;
        }
        $this->maxPrice = $fMaxPrice;
    }
}
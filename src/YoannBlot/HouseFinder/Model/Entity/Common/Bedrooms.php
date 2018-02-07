<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Length;

/**
 * Trait Bedrooms.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Bedrooms
{

    /**
     * @var int bedrooms.
     * @Length(1)
     */
    private $bedrooms = 0;

    /**
     * @return int
     */
    public function getBedrooms(): int
    {
        return intval($this->bedrooms);
    }

    /**
     * @param int $iBedrooms
     */
    public function setBedrooms(int $iBedrooms): void
    {
        if ($iBedrooms < 0 || $iBedrooms > 10) {
            $iBedrooms = 0;
        }
        $this->bedrooms = $iBedrooms;
    }

}
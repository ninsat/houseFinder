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
        return $this->bedrooms;
    }

    /**
     * @param int $iBedrooms
     */
    public function setBedrooms(int $iBedrooms)
    {
        if ($iBedrooms > 0 && $iBedrooms < 6) {
            $this->bedrooms = $iBedrooms;
        }
    }

}
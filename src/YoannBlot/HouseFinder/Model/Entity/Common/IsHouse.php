<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\DefaultValue;

/**
 * Trait IsHouse.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait IsHouse
{

    /**
     * @var bool is a house.
     * @DefaultValue(0)
     */
    private $is_house = false;

    /**
     * @return boolean
     */
    public function isHouse(): bool
    {
        return boolval($this->is_house);
    }

    /**
     * @param boolean $bIsHouse true if it's a house, otherwise false.
     */
    public function setHouse(bool $bIsHouse): void
    {
        $this->is_house = $bIsHouse;
    }

    /**
     * Set the property type to retrieve if it's a house or not.
     *
     * @param string $sPropertyType property type.
     */
    public function setPropertyType(string $sPropertyType): void
    {
        $bIsHouse = false;
        $sPropertyType = strtolower($sPropertyType);
        if (false !== strpos($sPropertyType, 'maison')) {
            $bIsHouse = true;
        }
        $this->setHouse($bIsHouse);
    }
}
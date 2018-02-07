<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Trait LinkToCity.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait LinkToCity
{

    /**
     * @var \YoannBlot\HouseFinder\Model\Entity\City linked city.
     */
    private $city;

    /**
     * @return City city.
     */
    public function getCity(): City
    {
        return $this->city;
    }

    /**
     * @param City $city city.
     */
    public function setCity(City $city): void
    {
        $this->city = $city;
    }

}
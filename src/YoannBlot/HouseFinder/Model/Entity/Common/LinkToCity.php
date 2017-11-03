<?php

namespace YoannBlot\HouseFinder\Model\Entity\Common;

use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Trait LinkToCity.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait LinkToCity {

    /**
     * @var City linked city.
     */
    private $city;

    /**
     * @return City city.
     */
    public function getCity (): City {
        return $this->city;
    }

    /**
     * @param City $city city.
     */
    public function setCity (City $city) {
        $this->city = $city;
    }

}
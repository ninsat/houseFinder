<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Class LinkToCities.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait LinkToCities
{
    /**
     * Get all linked cities.
     *
     * @return City[] linked cities.
     */
    public function getCities(): array
    {
        return $this->cities;
    }

    /**
     * Add a city.
     *
     * @param City $oCity city.
     */
    public function addCity(City $oCity): void
    {
        if (!array_key_exists($oCity->getId(), $this->cities)) {
            $this->cities[$oCity->getId()] = $oCity;
        }
    }

    /**
     * Remove a city.
     *
     * @param City $oCity city.
     */
    public function removeCity(City $oCity): void
    {
        if (array_key_exists($oCity->getId(), $this->cities)) {
            unset($this->cities[$oCity->getId()]);
        }
    }
}
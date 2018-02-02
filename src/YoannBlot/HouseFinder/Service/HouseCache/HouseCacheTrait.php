<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseCache;

/**
 * Trait HouseCacheTrait.
 *
 * @package YoannBlot\HouseFinder\Service\HouseCache
 */
trait HouseCacheTrait
{
    /**
     * @var HouseCacheService cache service.
     */
    private $oHouseCacheService;

    /**
     * @return HouseCacheService house cache service.
     */
    protected function getHouseCache(): HouseCacheService
    {
        return $this->oHouseCacheService;
    }
}
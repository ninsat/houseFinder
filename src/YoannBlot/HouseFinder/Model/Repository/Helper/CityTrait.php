<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Repository\Helper;

use YoannBlot\HouseFinder\Model\Repository\CityRepository;

/**
 * Class CityTrait.
 *
 * @package YoannBlot\HouseFinder\Model\Repository\Helper
 */
trait CityTrait
{
    /**
     * @var CityRepository city repository.
     */
    private $oCityRepository = null;

    /**
     * @return CityRepository city repository.
     */
    protected function getCityRepository(): CityRepository
    {
        return $this->oCityRepository;
    }
}
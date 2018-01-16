<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Repository\Helper;

use YoannBlot\HouseFinder\Model\Repository\HouseRepository;

/**
 * Class HouseRepository.
 *
 * @package YoannBlot\HouseFinder\Model\Repository\Helper
 */
trait HouseTrait
{

    /**
     * @var HouseRepository house repository.
     */
    private $oHouseRepository = null;

    /**
     * @return HouseRepository house repository.
     */
    protected function getHouseRepository(): HouseRepository
    {
        return $this->oHouseRepository;
    }

}
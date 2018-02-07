<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseImages;

/**
 * Class HouseImagesTrait.
 *
 * @package YoannBlot\HouseFinder\Service\HouseImages
 */
trait HouseImagesTrait
{
    /**
     * @var HouseImagesService house images service.
     */
    private $oHouseImagesService;

    /**
     * @return HouseImagesService house images service.
     */
    protected function getHouseImages(): HouseImagesService
    {
        return $this->oHouseImagesService;
    }

}
<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseImages;

use YoannBlot\Framework\Utils\File\Directory;
use YoannBlot\HouseFinder\Model\Entity\House;

/**
 * Class HouseImagesService.
 *
 * @package YoannBlot\HouseFinder\Service\HouseImages
 */
class HouseImagesService
{
    const DEFAULT_IMAGE = '/images/house/default.jpg';

    /**
     * Load all images on given house.
     *
     * @param House $oHouse house.
     */
    public function loadAll(House $oHouse): void
    {
        $sDirectory = $this->getDirectory($oHouse);
        if (is_dir($sDirectory)) {
            foreach (glob($sDirectory . '*') as $sImagePath) {
                $oHouse->addImage($this->convertPathToUrl($sImagePath));
            }
        }
    }

    /**
     * Get directory where images are saved.
     *
     * @param House $oHouse house.
     *
     * @return string directory.
     */
    private function getDirectory(House $oHouse): string
    {
        return WWW_PATH . 'houses' . DIRECTORY_SEPARATOR . $oHouse->getId() . DIRECTORY_SEPARATOR;
    }

    /**
     * Convert an image path to an image URL.
     *
     * @param string $sImagePath path image.
     *
     * @return string URL image.
     */
    private function convertPathToUrl(string $sImagePath): string
    {
        $sImagePath = str_replace(WWW_PATH, '', $sImagePath);
        $sImagePath = str_replace('\\', '/', $sImagePath);

        return '/' . $sImagePath;
    }

    /**
     * Load one image on given house.
     *
     * @param House $oHouse house.
     */
    public function loadOne(House $oHouse): void
    {
        $sImage = '';
        $sDirectory = $this->getDirectory($oHouse);
        if (is_dir($sDirectory)) {
            $aFiles = scandir($sDirectory);
            $sImage = $sDirectory . $aFiles[2];
        }
        if ('' === $sImage) {
            $sImage = static::DEFAULT_IMAGE;
        }

        $oHouse->addImage($this->convertPathToUrl($sImage));
    }

    /**
     * Save images of given house.
     *
     * @param House $oHouse house.
     */
    public function save(House $oHouse): void
    {
        if (count($oHouse->getImages()) > 0) {
            $sDirectory = $this->getDirectory($oHouse);
            Directory::delete($sDirectory);
            Directory::create($sDirectory);
            foreach ($oHouse->getImages() as $iImagePosition => $sImageUrl) {
                // TODO image extension instead of jpg
                $sImagePath = $sDirectory . ($iImagePosition + 1) . '.jpg';
                file_put_contents($sImagePath, file_get_contents($sImageUrl));
            }
        }
    }
}
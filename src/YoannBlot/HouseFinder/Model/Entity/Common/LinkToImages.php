<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Exclude;
use YoannBlot\HouseFinder\Service\HouseImages\HouseImagesService;

/**
 * Trait LinkToImages.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait LinkToImages
{
    /**
     * @var string[] images.
     * @Exclude()
     */
    private $aImages = [];

    /**
     * Get all images.
     *
     * @return string[] images.
     */
    public function getImages(): array
    {
        return $this->aImages;
    }

    /**
     * Add an image to the list.
     *
     * @param string $sImage image.
     */
    public function addImage(string $sImage): void
    {
        if ('' !== $sImage && !in_array($sImage, $this->aImages)) {
            $this->aImages[] = $sImage;
        }
    }

    /**
     * Remove an image.
     *
     * @param string $sImage image.
     */
    public function removeImage(string $sImage): void
    {
        $iFoundKey = array_search($sImage, $this->aImages);
        if (false !== $iFoundKey) {
            unset($this->aImages[$iFoundKey]);
        }
    }

    /**
     * @return string main image.
     */
    public function getMainImage(): string
    {
        return (count($this->aImages) > 0) ? $this->aImages[0] : HouseImagesService::DEFAULT_IMAGE;
    }
}
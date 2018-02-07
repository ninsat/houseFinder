<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Length;

/**
 * Trait Surface.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Surface
{

    /**
     * @var int surface.
     * @Length(3)
     */
    private $surface = 0;

    /**
     * @return int
     */
    public function getSurface(): int
    {
        return intval($this->surface);
    }

    /**
     * @param int $iSurface
     */
    public function setSurface(int $iSurface)
    {
        if ($iSurface < 0 || $iSurface > 1000) {
            $iSurface = 0;
        }
        $this->surface = $iSurface;
    }
}
<?php

namespace YoannBlot\HouseFinder\Model\Entity\Common;

/**
 * Trait Surface.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Surface {

    /**
     * @var int surface.
     */
    private $surface = 0;

    /**
     * @return int
     */
    public function getSurface (): int {
        return $this->surface;
    }

    /**
     * @param int $iSurface
     */
    public function setSurface (int $iSurface) {
        if ($iSurface > 0 && $iSurface < 200) {
            $this->surface = $iSurface;
        }
    }
}
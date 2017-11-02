<?php

namespace YoannBlot\HouseFinder\Model\Entity\Common;

/**
 * Trait Pieces.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Pieces {

    /**
     * @var int pieces.
     */
    private $pieces = 0;

    /**
     * @return int
     */
    public function getPieces (): int {
        return $this->pieces;
    }

    /**
     * @param int $iPieces
     */
    public function setPieces (int $iPieces) {
        if ($iPieces > 0 && $iPieces < 10) {
            $this->pieces = $iPieces;
        }
    }
}
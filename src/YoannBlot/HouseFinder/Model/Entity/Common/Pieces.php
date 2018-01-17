<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Length;

/**
 * Trait Pieces.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Pieces
{

    /**
     * @var int pieces.
     * @Length(1)
     */
    private $pieces = 0;

    /**
     * @return int
     */
    public function getPieces(): int
    {
        return $this->pieces;
    }

    /**
     * @param int $iPieces
     */
    public function setPieces(int $iPieces)
    {
        if ($iPieces > 0 && $iPieces < 10) {
            $this->pieces = $iPieces;
        }
    }
}
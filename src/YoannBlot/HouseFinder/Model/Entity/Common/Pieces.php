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
        return intval($this->pieces);
    }

    /**
     * @param int $iPieces
     */
    public function setPieces(int $iPieces): void
    {
        if ($iPieces < 0 || $iPieces > 10) {
            $iPieces = 0;
        }
        $this->pieces = $iPieces;
    }
}
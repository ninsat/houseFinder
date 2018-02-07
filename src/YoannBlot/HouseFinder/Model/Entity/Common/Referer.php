<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Length;

/**
 * Trait Type.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Referer
{

    /**
     * @var string referer.
     * @Length(50)
     */
    private $referer = '';

    /**
     * @return string
     */
    public function getReferer(): string
    {
        return $this->referer;
    }

    /**
     * @param string $sReferer new referer.
     */
    public function setReferer(string $sReferer): void
    {
        if (strlen($sReferer) > 2) {
            $this->referer = $sReferer;
        }
    }
}
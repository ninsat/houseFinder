<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Length;

/**
 * Trait Type.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait Type
{

    /**
     * @var string type.
     * @Length(20)
     */
    private $type = '';

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $sType
     */
    public function setType(string $sType)
    {
        if (strlen($sType) > 2) {
            $this->type = $sType;
        }
    }
}
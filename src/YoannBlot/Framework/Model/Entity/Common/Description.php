<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Length;

/**
 * Trait Description.
 *
 * @package YoannBlot\Framework\Model\Entity\Common
 */
trait Description
{

    /**
     * @var string description.
     * @Length(1000)
     */
    private $description = '';

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $sDescription
     */
    public function setDescription(string $sDescription)
    {
        if (strlen($sDescription) > 2) {
            $this->description = $sDescription;
        }
    }
}
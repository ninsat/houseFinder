<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Length;
use YoannBlot\Framework\Model\DataBase\Annotation\Nullable;

/**
 * Trait Name.
 *
 * @package YoannBlot\Framework\Model\Entity\Common
 */
trait Name
{

    /**
     * @var string name.
     * @Length(50)
     * @Nullable(false)
     */
    private $name = '';

    /**
     * @return string name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $sName new name.
     */
    public function setName(string $sName)
    {
        if (strlen($sName) > 2) {
            $this->name = $sName;
        }
    }
}
<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Length;
use YoannBlot\Framework\Model\DataBase\Annotation\Nullable;

/**
 * Trait PostalCode.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait PostalCode
{

    /**
     * @var string postal code.
     * @Length(5)
     * @Nullable(false)
     */
    private $postal_code = '';

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postal_code;
    }

    /**
     * @param string $sPostalCode
     */
    public function setPostalCode(string $sPostalCode): void
    {
        if (strlen($sPostalCode) > 4) {
            $this->postal_code = $sPostalCode;
        }
    }

}
<?php

namespace YoannBlot\HouseFinder\Model\Entity\Common;

/**
 * Trait PostalCode.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait PostalCode {

    /**
     * @var string postal code.
     */
    private $postal_code = '';

    /**
     * @return string
     */
    public function getPostalCode (): string {
        return $this->postal_code;
    }

    /**
     * @param string $sPostalCode
     */
    public function setPostalCode (string $sPostalCode) {
        if (strlen($sPostalCode) > 2) {
            $this->postal_code = $sPostalCode;
        }
    }

}
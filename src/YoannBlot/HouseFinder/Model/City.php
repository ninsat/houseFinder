<?php

namespace YoannBlot\HouseFinder\Model;

use YoannBlot\HouseFinder\Validator\Boolean;

/**
 * Class City
 *
 * @package YoannBlot\HouseFinder\Model
 */
final class City {

    /**
     * @var string name.
     */
    private $sName = '';

    /**
     * @var string postal code.
     */
    private $sPostalCode = '';

    /**
     * @var bool is city enabled.
     */
    private $bEnabled = true;

    /**
     * @return string
     */
    public function getName (): string {
        return $this->sName;
    }

    /**
     * @param string $sName
     */
    public function setName (string $sName) {
        if (strlen($sName) > 2) {
            $this->sName = $sName;
        }
    }

    /**
     * @return string
     */
    public function getPostalCode (): string {
        return $this->sPostalCode;
    }

    /**
     * @param string $sPostalCode
     */
    public function setPostalCode (string $sPostalCode) {
        if (strlen($sPostalCode) > 2) {
            $this->sPostalCode = $sPostalCode;
        }
    }

    /**
     * @return boolean
     */
    public function isEnabled (): bool {
        return $this->bEnabled;
    }

    /**
     * @param boolean $bEnabled
     */
    public function setEnabled (bool $bEnabled) {
        $this->bEnabled = Boolean::getValue($bEnabled);
    }

}
<?php

namespace YoannBlot\HouseFinder\Model\Entity;

use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Validator\Boolean;

/**
 * Class City
 *
 * @package YoannBlot\HouseFinder\Model\Entity
 * @author  Yoann Blot
 */
final class City extends AbstractEntity {

    /**
     * @var string name.
     */
    private $name = '';

    /**
     * @var string postal code.
     */
    private $postal_code = '';

    /**
     * @var bool is city enabled.
     */
    private $enabled = true;

    /**
     * @return string
     */
    public function getName (): string {
        return $this->name;
    }

    /**
     * @param string $sName
     */
    public function setName (string $sName) {
        if (strlen($sName) > 2) {
            $this->name = $sName;
        }
    }

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

    /**
     * @return boolean
     */
    public function isEnabled (): bool {
        return $this->enabled;
    }

    /**
     * @param boolean $bEnabled
     */
    public function setEnabled (bool $bEnabled) {
        $this->enabled = Boolean::getValue($bEnabled);
    }

}
<?php

namespace YoannBlot\HouseFinder\Model\Entity;

use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Validator\Boolean;

/**
 * Class House
 *
 * @package YoannBlot\HouseFinder\Model\Entity
 * @author  Yoann Blot
 */
final class House extends AbstractEntity {

    /**
     * @var string title.
     */
    private $title = '';

    /**
     * @var float rent.
     */
    private $rent = 0;

    /**
     * @var int pieces.
     */
    private $pieces = 0;

    /**
     * @var int bedrooms.
     */
    private $bedrooms = 0;

    /**
     * @var int surface.
     */
    private $surface = 0;

    /**
     * @var string description.
     */
    private $description = '';

    /**
     * @var string url.
     */
    private $url = '';

    /**
     * @var float fees.
     */
    private $fees = 0;

    /**
     * @var float guarantee.
     */
    private $guarantee = 0;

    /**
     * @var bool has bath.
     */
    private $has_bath = false;

    /**
     * @var \DateTime date.
     */
    private $date;

    /**
     * @var bool is city enabled.
     */
    private $enabled = true;

    /**
     * @var string type.
     */
    private $type = '';

    /**
     * @var string site id.
     */
    private $site_id = '';

    /**
     * House constructor.
     */
    public function __construct () {
        $this->date = new \DateTime("now");
    }

    /**
     * @return string
     */
    public function getTitle (): string {
        return $this->title;
    }

    /**
     * @param string $sTitle
     */
    public function setTitle (string $sTitle) {
        if (strlen($sTitle) > 2) {
            $this->title = $sTitle;
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

    /**
     * @return float
     */
    public function getRent (): float {
        return $this->rent;
    }

    /**
     * @param float $fRent
     */
    public function setRent (float $fRent) {
        if ($fRent >= 0 && $fRent < 2000) {
            $this->rent = $fRent;
        }
    }

    /**
     * @return int
     */
    public function getPieces (): int {
        return $this->pieces;
    }

    /**
     * @param int $iPieces
     */
    public function setPieces (int $iPieces) {
        if ($iPieces > 0 && $iPieces < 10) {
            $this->pieces = $iPieces;
        }
    }

    /**
     * @return int
     */
    public function getBedrooms (): int {
        return $this->bedrooms;
    }

    /**
     * @param int $iBedrooms
     */
    public function setBedrooms (int $iBedrooms) {
        if ($iBedrooms > 0 && $iBedrooms < 6) {
            $this->bedrooms = $iBedrooms;
        }
    }

    /**
     * @return int
     */
    public function getSurface (): int {
        return $this->surface;
    }

    /**
     * @param int $iSurface
     */
    public function setSurface (int $iSurface) {
        if ($iSurface > 0 && $iSurface < 200) {
            $this->surface = $iSurface;
        }
    }

    /**
     * @return string
     */
    public function getDescription (): string {
        return $this->description;
    }

    /**
     * @param string $sDescription
     */
    public function setDescription (string $sDescription) {
        if (strlen($sDescription) > 2) {
            $this->description = $sDescription;
        }
    }

    /**
     * @return string
     */
    public function getUrl (): string {
        return $this->url;
    }

    /**
     * @param string $sUrl
     */
    public function setUrl (string $sUrl) {
        if (strlen($sUrl) > 2) {
            $this->url = $sUrl;
        }
    }

    /**
     * @return float
     */
    public function getFees (): float {
        return $this->fees;
    }

    /**
     * @param float $fFees
     */
    public function setFees (float $fFees) {
        if ($fFees >= 0 && $fFees < 2000) {
            $this->fees = $fFees;
        }
    }

    /**
     * @return float
     */
    public function getGuarantee (): float {
        return $this->guarantee;
    }

    /**
     * @param float $fGuarantee
     */
    public function setGuarantee (float $fGuarantee) {
        if ($fGuarantee >= 0 && $fGuarantee < 2000) {
            $this->guarantee = $fGuarantee;
        }
    }

    /**
     * @return boolean
     */
    public function hasBath (): bool {
        return $this->has_bath;
    }

    /**
     * @param boolean $bHasBath
     */
    public function setBath (bool $bHasBath) {
        $this->has_bath = $bHasBath;
    }

    /**
     * @return \DateTime
     */
    public function getDate (): \DateTime {
        return $this->date;
    }

    /**
     * @param \DateTime $oDate
     */
    public function setDate (\DateTime $oDate) {
        $this->date = $oDate;
    }

    /**
     * @return string
     */
    public function getType (): string {
        return $this->type;
    }

    /**
     * @param string $sType
     */
    public function setType (string $sType) {
        if (strlen($sType) > 2) {
            $this->type = $sType;
        }
    }

    /**
     * @return string
     */
    public function getSiteId (): string {
        return $this->site_id;
    }

    /**
     * @param string $sSiteId
     */
    public function setSiteId (string $sSiteId) {
        if (strlen($sSiteId) > 0) {
            $this->site_id = $sSiteId;
        }
    }

}